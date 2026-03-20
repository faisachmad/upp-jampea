<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KapalSearchController extends Controller
{
    /**
     * Search kapals for autocomplete
     *
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (empty($query)) {
            return response()->json([]);
        }

        $kapals = Kapal::active()
            ->search($query)
            ->leftJoin('jenis_kapals', 'kapals.jenis_kapal_id', '=', 'jenis_kapals.id')
            ->select('kapals.id', 'kapals.nama', 'jenis_kapals.nama as jenis', 'kapals.gt', 'kapals.call_sign', 'kapals.pemilik_agen')
            ->limit(10)
            ->get()
            ->map(function ($kapal) {
                return [
                    'id' => $kapal->id,
                    'nama' => $kapal->nama,
                    'jenis' => $kapal->jenis,
                    'gt' => $kapal->gt,
                    'call_sign' => $kapal->call_sign,
                    'pemilik_agen' => $kapal->pemilik_agen,
                    'label' => $kapal->nama.' ('.$kapal->jenis.')',
                ];
            });

        return response()->json($kapals);
    }
}
