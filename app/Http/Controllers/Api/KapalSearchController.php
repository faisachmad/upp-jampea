<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kapal;
use Illuminate\Http\Request;

class KapalSearchController extends Controller
{
    /**
     * Search kapals for autocomplete
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        if (empty($query)) {
            return response()->json([]);
        }

        $kapals = Kapal::active()
            ->search($query)
            ->select('id', 'nama', 'jenis', 'gt', 'call_sign', 'pemilik_agen')
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
                    'label' => $kapal->nama . ' (' . $kapal->jenis . ')',
                ];
            });

        return response()->json($kapals);
    }
}
