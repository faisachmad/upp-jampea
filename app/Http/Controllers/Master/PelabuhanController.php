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
        if ($request->ajax()) {
            $query = Pelabuhan::with('tipePelabuhan')->select('pelabuhans.*');

            if ($request->filled('search_custom')) {
                $search = $request->string('search_custom')->toString();
                $query->where(function ($q) use ($search) {
                    $q->where('nama', 'like', '%'.$search.'%')
                        ->orWhere('kode', 'like', '%'.$search.'%');
                });
            }

            if ($request->filled('tipe')) {
                $query->where('tipe_pelabuhan_id', $request->integer('tipe'));
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
                ->addColumn('tipe_name', function ($row) {
                    return $row->tipePelabuhan->nama ?? $row->tipe;
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
                                        <button type="button" onclick=\'window.dispatchEvent(new CustomEvent("edit-pelabuhan", { detail: '.$jsonRow.' }))\' class="group flex items-center w-full px-4 py-2 text-xs text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                            <svg class="mr-2 h-4 w-4 text-gray-400 group-hover:text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </button>
                                        <form action="'.route('master.pelabuhan.destroy', $row->id).'" method="POST" class="inline">
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

        // Non-AJAX request: return paginated data with search, sort, and filter
        $query = Pelabuhan::query()->with('tipePelabuhan');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                    ->orWhere('kode', 'like', '%'.$search.'%');
            });
        }

        // Filter by tipe
        if ($request->filled('tipe')) {
            $query->where('tipe_pelabuhan_id', $request->tipe);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            }

            if ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Sorting
        $sort = $request->get('sort', 'id');
        $direction = $request->get('direction', 'asc');
        $allowedSorts = ['id', 'nama', 'kode', 'tipe_pelabuhan_id', 'created_at', 'updated_at'];
        if (! in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }
        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }

        // Per page validation
        $perPage = $request->get('per_page', 15);
        $allowedPerPage = [10, 15, 25, 50, 100];
        if (! in_array((int) $perPage, $allowedPerPage)) {
            $perPage = 15;
        }

        $pelabuhans = $query->orderBy($sort, $direction)->paginate($perPage)->appends($request->query());
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
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox - if not present, set to false
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Sync old tipe column for compatibility if needed
        $tipe = TipePelabuhan::find($validated['tipe_pelabuhan_id']);
        $validated['tipe'] = $tipe->nama;

        $pelabuhan = Pelabuhan::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pelabuhan berhasil ditambahkan.',
                'data' => $pelabuhan->load('tipePelabuhan'),
            ]);
        }

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
            'is_active' => 'nullable|boolean',
        ]);

        // Handle checkbox - if not present, set to false
        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Sync old tipe column
        $tipe = TipePelabuhan::find($validated['tipe_pelabuhan_id']);
        $validated['tipe'] = $tipe->nama;

        $pelabuhan->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Data pelabuhan berhasil diperbarui.',
                'data' => $pelabuhan->load('tipePelabuhan'),
            ]);
        }

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
