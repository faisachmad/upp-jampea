<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Nakhoda;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NakhodaSearchController extends Controller
{
    /**
     * Search nakhodas for autocomplete.
     *
     * @return JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');

        $nakhodas = Nakhoda::with('kapal:id,nama')
            ->active()
            ->when(!empty($query), function ($q) use ($query) {
                return $q->where('nama', 'like', '%'.$query.'%');
            })
            ->select('id', 'nama', 'kapal_id')
            ->orderBy('nama')
            ->limit(10)
            ->get()
            ->map(function ($nakhoda) {
                return [
                    'id' => $nakhoda->id,
                    'nama' => $nakhoda->nama,
                    'kapal_nama' => $nakhoda->kapal?->nama,
                    'label' => $nakhoda->nama.($nakhoda->kapal ? ' ('.$nakhoda->kapal->nama.')' : ''),
                ];
            });

        return response()->json($nakhodas);
    }
}
