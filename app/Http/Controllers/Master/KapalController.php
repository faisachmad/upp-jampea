<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Bendera;
use App\Models\JenisKapal;
use App\Models\Kapal;
use Illuminate\Http\Request;

class KapalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Kapal::with(['jenisKapal', 'bendera'])->select('kapals.*');

            if ($request->filled('search_custom')) {
                $search = $request->string('search_custom')->toString();
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('call_sign', 'like', '%'.$search.'%')
                        ->orWhere('pemilik_agen', 'like', '%'.$search.'%');
                });
            }

            if ($request->filled('jenis_kapal_id')) {
                $query->where('jenis_kapal_id', $request->integer('jenis_kapal_id'));
            }

            if ($request->filled('status')) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                }

                if ($request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            }

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('jenis_kapal_nama', function ($row) {
                    return $row->jenisKapal->nama ?? '-';
                })
                ->addColumn('bendera_nama', function ($row) {
                    return $row->bendera->nama_negara ?? '-';
                })
                ->addColumn('action', function ($row) {
                    $jsonRow = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');
                    $btn = '<div class="relative inline-block text-left" x-data="{ open: false }" @click.away="open = false" :class="{ \'z-[50]\': open }">
                                <button type="button" @click="open = !open" class="inline-flex items-center px-3 py-1.5 border border-gray-200 text-xs font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    Aksi
                                    <svg class="ml-1.5 h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div x-show="open"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="transform opacity-0 scale-95"
                                     x-transition:enter-end="transform opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="transform opacity-100 scale-100"
                                     x-transition:leave-end="transform opacity-0 scale-95"
                                     class="origin-top-right absolute right-0 mt-2 w-32 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-[9995] focus:outline-none"
                                     style="display: none;">
                                    <div class="py-1">
                                        <button type="button" onclick=\'window.dispatchEvent(new CustomEvent("edit-kapal", { detail: '.$jsonRow.' }))\' class="group flex items-center w-full px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                            <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <form action="'.route('master.kapal.destroy', $row->id).'" method="POST" class="inline">
                                            '.csrf_field().'
                                            '.method_field('DELETE').'
                                            <button type="button" onclick="confirmDelete(this.closest(\'form\'))" class="group flex items-center w-full px-4 py-2 text-xs text-red-600 hover:bg-red-50 transition-colors">
                                                <svg class="mr-2 h-4 w-4 text-red-400 group-hover:text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $jenisKapals = JenisKapal::active()->orderBy('nama')->get();
        $benderas = Bendera::active()->orderBy('nama_negara')->get();

        return view('master.kapal.index', compact('jenisKapals', 'benderas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisKapals = JenisKapal::active()->orderBy('nama')->get();
        $benderas = Bendera::active()->orderBy('nama_negara')->get();

        return view('master.kapal.create', compact('jenisKapals', 'benderas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'jenis_kapal_id' => 'nullable|exists:jenis_kapals,id',
            'gt' => 'nullable|numeric|min:0',
            'dwt' => 'nullable|numeric|min:0',
            'panjang' => 'nullable|numeric|min:0',
            'tanda_selar' => 'nullable|string|max:50',
            'call_sign' => 'nullable|string|max:20',
            'tempat_kedudukan' => 'nullable|string|max:100',
            'bendera_id' => 'nullable|exists:benderas,id',
            'pemilik_agen' => 'nullable|string|max:200',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox - if not present, set to false
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Convert empty strings to null for numeric fields
        foreach (['gt', 'dwt', 'panjang', 'jenis_kapal_id', 'bendera_id'] as $field) {
            if (isset($validated[$field]) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        $kapal = Kapal::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kapal berhasil ditambahkan.',
                'data' => $kapal->load(['jenisKapal', 'bendera']),
            ]);
        }

        return redirect()->route('master.kapal.index')
            ->with('success', 'Kapal berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kapal $kapal)
    {
        $kapal->load(['jenisKapal', 'bendera']);

        return view('master.kapal.show', compact('kapal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kapal $kapal)
    {
        $jenisKapals = JenisKapal::active()->orderBy('nama')->get();
        $benderas = Bendera::active()->orderBy('nama_negara')->get();

        return view('master.kapal.edit', compact('kapal', 'jenisKapals', 'benderas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kapal $kapal)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:150',
            'jenis_kapal_id' => 'nullable|exists:jenis_kapals,id',
            'gt' => 'nullable|numeric|min:0',
            'dwt' => 'nullable|numeric|min:0',
            'panjang' => 'nullable|numeric|min:0',
            'tanda_selar' => 'nullable|string|max:50',
            'call_sign' => 'nullable|string|max:20',
            'tempat_kedudukan' => 'nullable|string|max:100',
            'bendera_id' => 'nullable|exists:benderas,id',
            'pemilik_agen' => 'nullable|string|max:200',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox - if not present, set to false
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Convert empty strings to null for numeric fields
        foreach (['gt', 'dwt', 'panjang', 'jenis_kapal_id', 'bendera_id'] as $field) {
            if (isset($validated[$field]) && $validated[$field] === '') {
                $validated[$field] = null;
            }
        }

        $kapal->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Kapal berhasil diupdate.',
                'data' => $kapal->load(['jenisKapal', 'bendera']),
            ]);
        }

        return redirect()->route('master.kapal.index')
            ->with('success', 'Kapal berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kapal $kapal)
    {
        $kapal->delete();

        return redirect()->route('master.kapal.index')
            ->with('success', 'Kapal berhasil dihapus.');
    }

    /**
     * Store a newly created jenis kapal from modal.
     */
    public function storeJenisKapal(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        // Auto-generate unique kode from nama
        $validated['kode'] = $this->generateUniqueKodeJenisKapal($validated['nama']);

        $jenisKapal = JenisKapal::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jenis kapal berhasil ditambahkan.',
            'data' => $jenisKapal,
        ]);
    }

    /**
     * Generate unique kode for jenis kapal from nama
     */
    private function generateUniqueKodeJenisKapal(string $nama)
    {
        // Get first letters from each word, max 10 chars
        $words = explode(' ', $nama);
        $kode = '';

        foreach ($words as $word) {
            if (strlen($word) > 0) {
                $kode .= strtoupper($word[0]);
            }
        }

        // If kode is less than 3 chars, use more from first word
        if (strlen($kode) < 3 && count($words) > 0) {
            $kode = strtoupper(substr($words[0], 0, 3));
        }

        $kode = substr($kode, 0, 10);
        $baseKode = $kode;
        $counter = 1;

        // Check uniqueness
        while (JenisKapal::where('kode', $kode)->exists()) {
            $kode = $baseKode.$counter;
            $counter++;
        }

        return $kode;
    }

    /**
     * Store a newly created bendera from modal.
     */
    public function storeBendera(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:3|unique:benderas,kode',
            'nama_negara' => 'required|string|max:100',
        ]);

        $bendera = Bendera::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Bendera berhasil ditambahkan.',
            'data' => $bendera,
        ]);
    }
}
