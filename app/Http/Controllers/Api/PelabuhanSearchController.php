<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelabuhan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PelabuhanSearchController extends Controller
{
    /**
     * Search pelabuhans for autocomplete
     *
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $tipe = $request->input('tipe'); // Optional filter by tipe

        if (empty($query)) {
            return response()->json([]);
        }

        $searchQuery = Pelabuhan::active()->search($query);

        // Filter by tipe if provided (useful for pelabuhan asal/tujuan)
        if ($tipe) {
            $searchQuery->where('tipe', $tipe);
        }

        $pelabuhans = $searchQuery
            ->select('id', 'kode', 'nama', 'tipe')
            ->limit(10)
            ->get()
            ->map(function ($pelabuhan) {
                return [
                    'id' => $pelabuhan->id,
                    'kode' => $pelabuhan->kode,
                    'nama' => $pelabuhan->nama,
                    'tipe' => $pelabuhan->tipe,
                    'label' => $pelabuhan->nama.' ('.$pelabuhan->kode.')',
                ];
            });

        return response()->json($pelabuhans);
    }

    /**
     * Get pelabuhans untuk dropdown (internal only)
     *
     * @return JsonResponse
     */
    public function internal()
    {
        $pelabuhans = Pelabuhan::internal()
            ->active()
            ->select('id', 'kode', 'nama', 'tipe')
            ->orderBy('kode')
            ->get()
            ->map(function ($pelabuhan) {
                return [
                    'id' => $pelabuhan->id,
                    'kode' => $pelabuhan->kode,
                    'nama' => $pelabuhan->nama,
                    'tipe' => $pelabuhan->tipe,
                ];
            });

        return response()->json($pelabuhans);
    }
}
