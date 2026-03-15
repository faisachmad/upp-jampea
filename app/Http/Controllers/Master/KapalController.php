<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use App\Models\JenisKapal;
use App\Models\Bendera;
use Illuminate\Http\Request;

class KapalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kapal::with(['jenisKapal', 'bendera']);

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by jenis_kapal_id
        if ($request->filled('jenis_kapal_id')) {
            $query->where('jenis_kapal_id', $request->jenis_kapal_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 'active') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        // Configurable per page, default 15
        $perPage = $request->input('per_page', 15);
        $perPage = in_array($perPage, [10, 15, 25, 50, 100]) ? $perPage : 15;

        $kapals = $query->orderBy('nama')->paginate($perPage)->withQueryString();

        // Get data for filters
        $jenisKapals = JenisKapal::active()->orderBy('nama')->get();
        $benderas = Bendera::active()->orderBy('nama_negara')->get();

        return view('master.kapal.index', compact('kapals', 'jenisKapals', 'benderas'));
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
            'is_active' => 'boolean',
        ]);

        Kapal::create($validated);

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
            'is_active' => 'boolean',
        ]);

        $kapal->update($validated);

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
            'kode' => 'required|string|max:10|unique:jenis_kapals,kode',
            'nama' => 'required|string|max:100',
            'keterangan' => 'nullable|string',
        ]);

        $jenisKapal = JenisKapal::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Jenis kapal berhasil ditambahkan.',
            'data' => $jenisKapal
        ]);
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
            'data' => $bendera
        ]);
    }
}
