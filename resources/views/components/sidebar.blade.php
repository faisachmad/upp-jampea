<aside class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform duration-300 bg-gray-800 text-white -translate-x-full"
       :class="{ 'translate-x-0': sidebarOpen }">
    <div class="h-full px-3 py-4 overflow-y-auto">
        <!-- Logo & Title -->
        <div class="flex items-center mb-5 p-2">
            <img src="{{ asset('icon.png') }}" class="w-8 h-8 mr-2" alt="Logo">
            <div>
                <h2 class="text-lg font-bold">SAPOJAM</h2>
                <p class="text-xs text-gray-400">Sistem Administrasi Pelaporan Operasional Pelabuhan Jampea</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="space-y-2 font-medium" id="sidebar-nav">
            <!-- Dashboard -->
            <li>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 transition duration-75 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            <!-- Input Kunjungan -->
            <li>
                <a href="{{ route('kunjungan.index') }}"
                   class="flex items-center p-2 rounded-lg hover:bg-gray-700 {{ request()->routeIs('kunjungan.*') ? 'bg-gray-700' : '' }}">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-3">Input Kunjungan</span>
                </a>
            </li>

            <!-- Master Data -->
            @php $masterActive = request()->routeIs('master.*'); @endphp
            <li x-data="{ open: {{ $masterActive ? 'true' : 'false' }} }">
                <button type="button"
                        class="flex items-center w-full p-2 rounded-lg hover:bg-gray-700 group {{ $masterActive ? 'text-white' : '' }}"
                        @click="open = !open">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Master Data</span>
                    <svg class="w-3 h-3 transition-transform" fill="currentColor" viewBox="0 0 10 6" :class="{ 'rotate-180': open }">
                        <path fill-rule="evenodd" d="M1.646 1.646a.5.5 0 01.708 0L5 4.293l2.646-2.647a.5.5 0 01.708.708l-3 3a.5.5 0 01-.708 0l-3-3a.5.5 0 010-.708z"/>
                    </svg>
                </button>
                <ul x-show="open" x-collapse class="py-2 space-y-2">
                    <li>
                        <a href="{{ route('master.pelabuhan.index') }}"
                           class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('master.pelabuhan.*') ? 'bg-gray-700' : '' }}">
                            Pelabuhan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.tipe-pelabuhan.index') }}"
                           class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('master.tipe-pelabuhan.*') ? 'bg-gray-700' : '' }}">
                            Tipe Pelabuhan
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.kapal.index') }}"
                           class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('master.kapal.*') ? 'bg-gray-700' : '' }}">
                            Kapal
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.jenis-kapal.index') }}"
                           class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('master.jenis-kapal.*') ? 'bg-gray-700' : '' }}">
                            Jenis Kapal
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.nakhoda.index') }}"
                           class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('master.nakhoda.*') ? 'bg-gray-700' : '' }}">
                            Nakhoda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.barang-b3.index') }}"
                           class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('master.barang-b3.*') ? 'bg-gray-700' : '' }}">
                            Barang B3
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('master.jenis-pelayaran.index') }}"
                           class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('master.jenis-pelayaran.*') ? 'bg-gray-700' : '' }}">
                            Jenis Pelayaran
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Laporan -->
            @php $laporanActive = request()->routeIs('laporan.*') || request()->routeIs('import.*'); @endphp
            <li x-data="{ open: {{ $laporanActive ? 'true' : 'false' }} }">
                <button type="button"
                        class="flex items-center w-full p-2 rounded-lg hover:bg-gray-700 group {{ $laporanActive ? 'text-white' : '' }}"
                        @click="open = !open">
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                    </svg>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap">Laporan</span>
                    <svg class="w-3 h-3 transition-transform" fill="currentColor" viewBox="0 0 10 6" :class="{ 'rotate-180': open }">
                        <path fill-rule="evenodd" d="M1.646 1.646a.5.5 0 01.708 0L5 4.293l2.646-2.647a.5.5 0 01.708.708l-3 3a.5.5 0 01-.708 0l-3-3a.5.5 0 010-.708z"/>
                    </svg>
                </button>
                <ul x-show="open" x-collapse class="py-2 space-y-2">
                    <li>
                        <a href="{{ route('laporan.pelra') }}" class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('laporan.pelra') ? 'bg-gray-700' : '' }}">PELRA</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.perintis') }}" class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('laporan.perintis') ? 'bg-gray-700' : '' }}">Perintis</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.ferry') }}" class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('laporan.ferry') ? 'bg-gray-700' : '' }}">Ferry</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.dalam-negeri') }}" class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('laporan.dalam-negeri') ? 'bg-gray-700' : '' }}">Dalam Negeri</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.luar-negeri') }}" class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('laporan.luar-negeri') ? 'bg-gray-700' : '' }}">Luar Negeri</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.rekap-spb') }}" class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('laporan.rekap-spb') ? 'bg-gray-700' : '' }}">Rekap SPB</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.rekap-operasional') }}" class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('laporan.rekap-operasional') ? 'bg-gray-700' : '' }}">Rekap Operasional</a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.export-excel') }}" class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('laporan.export-excel') ? 'bg-gray-700' : 'text-green-400' }}">
                            Export Excel
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('import.excel.index') }}" class="flex items-center w-full p-2 pl-11 rounded-lg hover:bg-gray-700 {{ request()->routeIs('import.excel.*') ? 'bg-gray-700' : '' }}">Import Excel Lama</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('aside .overflow-y-auto');

            // Restore scroll position
            const scrollPos = localStorage.getItem('sidebar-scroll-pos');
            if (scrollPos) {
                sidebar.scrollTop = scrollPos;
            }

            // Save scroll position before reload
            window.addEventListener('beforeunload', function() {
                localStorage.setItem('sidebar-scroll-pos', sidebar.scrollTop);
            });

            // Fallback: save on scroll for browsers that don't support beforeunload reliably
            let scrollTimeout;
            sidebar.addEventListener('scroll', function() {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    localStorage.setItem('sidebar-scroll-pos', sidebar.scrollTop);
                }, 100);
            });
        });
    </script>
</aside>
