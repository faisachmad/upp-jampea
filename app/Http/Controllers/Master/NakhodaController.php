<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Nakhoda;
use App\Models\Kapal;
use Illuminate\Http\Request;

class NakhodaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Nakhoda::with('kapal');

        // Search by nama
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by kapal
        if ($request->filled('kapal_id')) {
            $query->where('kapal_id', $request->kapal_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->active();
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $nakhodas = $query->orderBy('nama')->paginate(15);
        $kapals = Kapal::active()->orderBy('nama')->get();

        return view('master.nakhoda.index', compact('nakhodas', 'kapals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kapals = Kapal::active()->orderBy('nama')->get();
        return view('master.nakhoda.create', compact('kapals'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kapal_id' => 'required|exists:kapals,id',
            'is_active' => 'boolean',
        ]);

        Nakhoda::create($validated);

        return redirect()->route('master.nakhoda.index')
            ->with('success', 'Data nakhoda berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Nakhoda $nakhoda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Nakhoda $nakhoda)
    {
        $kapals = Kapal::active()->orderBy('nama')->get();
        return view('master.nakhoda.edit', compact('nakhoda', 'kapals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Nakhoda $nakhoda)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'kapal_id' => 'required|exists:kapals,id',
            'is_active' => 'boolean',
        ]);

        $nakhoda->update($validated);

        return redirect()->route('master.nakhoda.index')
            ->with('success', 'Data nakhoda berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Nakhoda $nakhoda)
    {
        $nakhoda->delete();

        return redirect()->route('master.nakhoda.index')
            ->with('success', 'Data nakhoda berhasil dihapus.');
    }
}
