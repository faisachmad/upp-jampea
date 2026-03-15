<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Pelabuhan;
use App\Models\TipePelabuhan;
use Illuminate\Http\Request;

class PelabuhanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pelabuhan::query();

        // Search by kode or nama
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by tipe
        if ($request->filled('tipe')) {
            $query->where('tipe_pelabuhan_id', $request->tipe);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'kode');
        $direction = $request->get('direction', 'asc');

        $allowedSorts = ['kode', 'nama', 'tipe', 'is_active'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'kode';
        }

        // Per page setting
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $pelabuhans = $query->with('tipePelabuhan')->orderBy($sort, $direction)->paginate($perPage);
        $tipes = TipePelabuhan::all();

        return view('master.pelabuhan.index', compact('pelabuhans', 'tipes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tipes = TipePelabuhan::all();
        return view('master.pelabuhan.create', compact('tipes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tipe_pelabuhan_id' => 'required|exists:tipe_pelabuhans,id',
            'is_active' => 'boolean',
        ]);

        // Sync old tipe column for compatibility if needed
        $tipe = TipePelabuhan::find($validated['tipe_pelabuhan_id']);
        $validated['tipe'] = $tipe->nama;

        Pelabuhan::create($validated);

        return redirect()->route('master.pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pelabuhan $pelabuhan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pelabuhan $pelabuhan)
    {
        $tipes = TipePelabuhan::all();
        return view('master.pelabuhan.edit', compact('pelabuhan', 'tipes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pelabuhan $pelabuhan)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'tipe_pelabuhan_id' => 'required|exists:tipe_pelabuhans,id',
            'is_active' => 'boolean',
        ]);

        // Sync old tipe column
        $tipe = TipePelabuhan::find($validated['tipe_pelabuhan_id']);
        $validated['tipe'] = $tipe->nama;

        $pelabuhan->update($validated);

        return redirect()->route('master.pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pelabuhan $pelabuhan)
    {
        $pelabuhan->delete();

        return redirect()->route('master.pelabuhan.index')
            ->with('success', 'Data pelabuhan berhasil dihapus.');
    }
}
