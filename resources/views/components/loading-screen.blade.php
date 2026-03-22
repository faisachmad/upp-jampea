<div id="global-loader"
     class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-white/80 backdrop-blur-md transition-opacity duration-500 opacity-0 pointer-events-none"
     style="display: none;">
    <div class="relative flex flex-col items-center">
        <!-- Pulse ring animation -->
        <div class="absolute inset-0 rounded-full bg-blue-500/20 animate-ping scale-150"></div>

        <!-- App Logo with Pulse -->
        <div class="relative animate-bounce-slow">
            <img src="{{ asset('icon.png') }}" alt="SAPOJAM Logo" class="h-24 w-24 object-contain drop-shadow-2xl">
        </div>

        <!-- Dynamic Loading Text -->
        <div class="mt-8 text-center">
            <h3 class="text-xl font-bold text-gray-800 tracking-wide animate-pulse">
                SAPOJAM
            </h3>
            <p id="loader-message" class="mt-2 text-gray-500 font-medium italic transition-all duration-300 min-h-[1.5rem]">
                Menyiapkan data untuk Anda...
            </p>
        </div>

        <!-- Modern Loading Bar -->
        <div class="mt-6 w-48 h-1.5 bg-gray-200 rounded-full overflow-hidden">
            <div class="h-full bg-gradient-to-r from-blue-500 to-indigo-600 animate-loading-bar origin-left"></div>
        </div>
    </div>
</div>

<style>
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(-5%); animation-timing-function: cubic-bezier(0.8, 0, 1, 1); }
        50% { transform: translateY(0); animation-timing-function: cubic-bezier(0, 0, 0.2, 1); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 2s infinite;
    }
    @keyframes loading-bar {
        0% { transform: scaleX(0); }
        50% { transform: scaleX(0.7); }
        100% { transform: scaleX(1); }
    }
    .animate-loading-bar {
        animation: loading-bar 2s infinite ease-in-out;
    }
    #global-loader.show {
        opacity: 1;
        pointer-events: auto;
        display: flex !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loader = document.getElementById('global-loader');
        const messageEl = document.getElementById('loader-message');
        let suppressBeforeUnloadLoader = false;

        const messages = [
            "Menghubungkan ke pelabuhan...",
            "Memvalidasi manifes kapal...",
            "Sinkronisasi data SAPOJAM...",
            "Menyiapkan laporan navigasi...",
            "Mengoptimalkan rute layar...",
            "Memuat informasi nakhoda...",
            "Pengecekan keamanan sistem...",
            "Hampir sampai di tujuan...",
            "Sesaat lagi..."
        ];

        let messageInterval;
        let loaderAutoHideTimeout;

        const clearLoaderTimers = () => {
            clearInterval(messageInterval);
            clearTimeout(loaderAutoHideTimeout);
        };

        window.showLoader = function(options = {}) {
            const autoHideMs = typeof options.autoHideMs === 'number' ? options.autoHideMs : null;

            clearTimeout(loaderAutoHideTimeout);

            if (loader.classList.contains('show')) return;

            loader.classList.add('show');

            // Random initial message
            messageEl.innerText = messages[Math.floor(Math.random() * messages.length)];

            // Cycle messages
            messageInterval = setInterval(() => {
                messageEl.style.opacity = '0';
                setTimeout(() => {
                    messageEl.innerText = messages[Math.floor(Math.random() * messages.length)];
                    messageEl.style.opacity = '1';
                }, 300);
            }, 3000);

            if (autoHideMs !== null) {
                loaderAutoHideTimeout = setTimeout(() => {
                    hideLoader();
                }, autoHideMs);
            }
        };

        window.hideLoader = function() {
            loader.classList.remove('show');
            clearLoaderTimers();
        };

        document.addEventListener('click', function(event) {
            const downloadTrigger = event.target.closest('[data-download-request="true"]');

            if (!downloadTrigger) {
                return;
            }

            suppressBeforeUnloadLoader = true;
            showLoader({ autoHideMs: 2200 });

            setTimeout(() => {
                suppressBeforeUnloadLoader = false;
            }, 2500);
        });

        // Page visibility / transition handling
        window.addEventListener('beforeunload', function() {
            if (suppressBeforeUnloadLoader) {
                return;
            }

            showLoader();
        });

        // Hide if page is loaded from cache (back button)
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                hideLoader();
            }
        });

        // AJAX Interceptors (Axios)
        if (window.axios) {
            window.axios.interceptors.request.use(config => {
                // Don't show for background/silent requests if needed
                if (!config.silent) {
                    showLoader();
                }
                return config;
            }, error => {
                hideLoader();
                return Promise.reject(error);
            });

            window.axios.interceptors.response.use(response => {
                hideLoader();
                return response;
            }, error => {
                hideLoader();
                return Promise.reject(error);
            });
        }

        // Global AJAX Interceptors (jQuery)
        if (typeof jQuery !== 'undefined') {
            $(document).ajaxStart(function() { showLoader(); });
            $(document).ajaxStop(function() { hideLoader(); });
            $(document).ajaxError(function() { hideLoader(); });
        }
    });
</script>
