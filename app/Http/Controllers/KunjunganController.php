<?php

namespace App\Http\Controllers;

use App\Models\BarangB3;
use App\Models\Bendera;
use App\Models\JenisKapal;
use App\Models\JenisPelayaran;
use App\Models\Kapal;
use App\Models\Kunjungan;
use App\Models\KunjunganB3;
use App\Models\KunjunganMuatan;
use App\Models\Nakhoda;
use App\Models\Pelabuhan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KunjunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kunjungan::with(['pelabuhan', 'kapal', 'jenisPelayaran', 'nakhoda']);

        // Filter by periode (bulan & tahun)
        if ($request->filled('bulan') && $request->filled('tahun')) {
            $query->byPeriode($request->tahun, $request->bulan);
        } elseif ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }

        // Filter by pelabuhan
        if ($request->filled('pelabuhan_id')) {
            $query->byPelabuhan($request->pelabuhan_id);
        }

        // Filter by jenis pelayaran
        if ($request->filled('jenis_pelayaran_id')) {
            $query->byJenisPelayaran($request->jenis_pelayaran_id);
        }

        $kunjungans = $query->orderBy('tgl_datang', 'desc')->paginate(20);

        // Data for filters and form
        $pelabuhans = Pelabuhan::internal()->active()->orderBy('kode')->get();
        $jenisPelayarans = JenisPelayaran::orderBy('kode')->get();
        $nakhodas = Nakhoda::active()->orderBy('nama')->get();
        $kapals = Kapal::active()->orderBy('nama')->get();
        $barangB3s = BarangB3::orderBy('nama')->get();
        $jenisKapals = JenisKapal::active()->orderBy('nama')->get();
        $benderas = Bendera::active()->orderBy('nama_negara')->get();

        return view('kunjungan.index', compact('kunjungans', 'pelabuhans', 'jenisPelayarans', 'nakhodas', 'kapals', 'barangB3s', 'jenisKapals', 'benderas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Data untuk form wizard
        $pelabuhans = Pelabuhan::internal()->active()->orderBy('kode')->get();
        $jenisPelayarans = JenisPelayaran::orderBy('kode')->get();
        $nakhodas = Nakhoda::active()->orderBy('nama')->get();
        $kapals = Kapal::active()->orderBy('nama')->get();
        $barangB3s = BarangB3::orderBy('nama')->get();
        $jenisKapals = JenisKapal::active()->orderBy('nama')->get();
        $benderas = Bendera::active()->orderBy('nama_negara')->get();

        return view('kunjungan.create', compact('pelabuhans', 'jenisPelayarans', 'nakhodas', 'kapals', 'barangB3s', 'jenisKapals', 'benderas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            // Data Kunjungan Utama
            'pelabuhan_id' => 'required|exists:pelabuhans,id',
            'kapal_id' => 'required|exists:kapals,id',
            'jenis_pelayaran_id' => 'required|exists:jenis_pelayarans,id',
            'nakhoda_id' => 'required|exists:nakhodas,id',
            'bulan' => 'required|integer|min:1|max:12',
            'tahun' => 'required|integer|min:2020|max:2100',

            // Data Kedatangan
            'tgl_datang' => 'required|date',
            'jam_datang' => 'required|date_format:H:i',
            'pelabuhan_asal_id' => 'required|exists:pelabuhans,id',
            'no_spb_datang' => 'nullable|string|max:50',

            // Data Keberangkatan
            'tgl_tolak' => 'required|date|after_or_equal:tgl_datang',
            'jam_tolak' => 'required|date_format:H:i',
            'pelabuhan_tujuan_id' => 'required|exists:pelabuhans,id',
            'no_spb_tolak' => 'nullable|string|max:50',

            // Data Penumpang
            'pnp_datang_dewasa' => 'nullable|integer|min:0',
            'pnp_datang_anak' => 'nullable|integer|min:0',
            'pnp_tolak_dewasa' => 'nullable|integer|min:0',
            'pnp_tolak_anak' => 'nullable|integer|min:0',

            // Data Kendaraan
            'kend_datang_gol1' => 'nullable|integer|min:0',
            'kend_datang_gol2' => 'nullable|integer|min:0',
            'kend_datang_gol3' => 'nullable|integer|min:0',
            'kend_datang_gol4a' => 'nullable|integer|min:0',
            'kend_datang_gol4b' => 'nullable|integer|min:0',
            'kend_datang_gol5' => 'nullable|integer|min:0',
            'kend_tolak_gol1' => 'nullable|integer|min:0',
            'kend_tolak_gol2' => 'nullable|integer|min:0',
            'kend_tolak_gol3' => 'nullable|integer|min:0',
            'kend_tolak_gol4a' => 'nullable|integer|min:0',
            'kend_tolak_gol4b' => 'nullable|integer|min:0',
            'kend_tolak_gol5' => 'nullable|integer|min:0',

            // Lanjutan Muatan
            'lanjutan_ton' => 'nullable|numeric|min:0',

            // Data Muatan (array)
            'muatan' => 'nullable|array',
            'muatan.*.tipe' => 'required|in:BONGKAR,MUAT',
            'muatan.*.jenis_barang' => 'required|string|max:100',
            'muatan.*.ton_m3' => 'nullable|numeric|min:0',
            'muatan.*.jenis_hewan' => 'nullable|string|max:50',
            'muatan.*.jumlah_hewan' => 'nullable|integer|min:0',

            // Data B3 (array)
            'b3' => 'nullable|array',
            'b3.*.barang_b3_id' => 'required|exists:barang_b3s,id',
            'b3.*.jenis_kegiatan' => 'required|in:BONGKAR,MUAT',
            'b3.*.bentuk_muatan' => 'required|in:CURAH,PADAT',
            'b3.*.jumlah_ton' => 'nullable|numeric|min:0',
            'b3.*.jumlah_container' => 'nullable|integer|min:0',
            'b3.*.kemasan' => 'nullable|string|max:50',
            'b3.*.jumlah' => 'nullable|integer|min:0',
            'b3.*.petugas' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Create Kunjungan
            $kunjungan = Kunjungan::create([
                'pelabuhan_id' => $validated['pelabuhan_id'],
                'kapal_id' => $validated['kapal_id'],
                'jenis_pelayaran_id' => $validated['jenis_pelayaran_id'],
                'nakhoda_id' => $validated['nakhoda_id'],
                'bulan' => $validated['bulan'],
                'tahun' => $validated['tahun'],
                'tgl_datang' => $validated['tgl_datang'],
                'jam_datang' => $validated['jam_datang'],
                'pelabuhan_asal_id' => $validated['pelabuhan_asal_id'],
                'no_spb_datang' => $validated['no_spb_datang'] ?? null,
                'tgl_tolak' => $validated['tgl_tolak'],
                'jam_tolak' => $validated['jam_tolak'],
                'pelabuhan_tujuan_id' => $validated['pelabuhan_tujuan_id'],
                'no_spb_tolak' => $validated['no_spb_tolak'] ?? null,
                'pnp_datang_dewasa' => $validated['pnp_datang_dewasa'] ?? 0,
                'pnp_datang_anak' => $validated['pnp_datang_anak'] ?? 0,
                'pnp_tolak_dewasa' => $validated['pnp_tolak_dewasa'] ?? 0,
                'pnp_tolak_anak' => $validated['pnp_tolak_anak'] ?? 0,
                'kend_datang_gol1' => $validated['kend_datang_gol1'] ?? 0,
                'kend_datang_gol2' => $validated['kend_datang_gol2'] ?? 0,
                'kend_datang_gol3' => $validated['kend_datang_gol3'] ?? 0,
                'kend_datang_gol4a' => $validated['kend_datang_gol4a'] ?? 0,
                'kend_datang_gol4b' => $validated['kend_datang_gol4b'] ?? 0,
                'kend_datang_gol5' => $validated['kend_datang_gol5'] ?? 0,
                'kend_tolak_gol1' => $validated['kend_tolak_gol1'] ?? 0,
                'kend_tolak_gol2' => $validated['kend_tolak_gol2'] ?? 0,
                'kend_tolak_gol3' => $validated['kend_tolak_gol3'] ?? 0,
                'kend_tolak_gol4a' => $validated['kend_tolak_gol4a'] ?? 0,
                'kend_tolak_gol4b' => $validated['kend_tolak_gol4b'] ?? 0,
                'kend_tolak_gol5' => $validated['kend_tolak_gol5'] ?? 0,
                'lanjutan_ton' => $validated['lanjutan_ton'] ?? null,
            ]);

            // Create Kunjungan Muatan (if any)
            if (! empty($validated['muatan'])) {
                foreach ($validated['muatan'] as $muatan) {
                    KunjunganMuatan::create([
                        'kunjungan_id' => $kunjungan->id,
                        'tipe' => $muatan['tipe'],
                        'jenis_barang' => $muatan['jenis_barang'],
                        'ton_m3' => $muatan['ton_m3'] ?? null,
                        'jenis_hewan' => $muatan['jenis_hewan'] ?? null,
                        'jumlah_hewan' => $muatan['jumlah_hewan'] ?? null,
                    ]);
                }
            }

            // Create Kunjungan B3 (if any)
            if (! empty($validated['b3'])) {
                foreach ($validated['b3'] as $b3) {
                    KunjunganB3::create([
                        'kunjungan_id' => $kunjungan->id,
                        'barang_b3_id' => $b3['barang_b3_id'],
                        'jenis_kegiatan' => $b3['jenis_kegiatan'],
                        'bentuk_muatan' => $b3['bentuk_muatan'],
                        'jumlah_ton' => $b3['jumlah_ton'] ?? null,
                        'jumlah_container' => $b3['jumlah_container'] ?? null,
                        'kemasan' => $b3['kemasan'] ?? null,
                        'jumlah' => $b3['jumlah'] ?? null,
                        'petugas' => $b3['petugas'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('kunjungan.index')
                ->with('success', 'Data kunjungan kapal berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Kunjungan $kunjungan)
    {
        $kunjungan->load(['pelabuhan', 'kapal', 'jenisPelayaran', 'nakhoda',
            'pelabuhanAsal', 'pelabuhanTujuan', 'kunjunganMuatans', 'kunjunganB3s.barangB3']);

        return view('kunjungan.show', compact('kunjungan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kunjungan $kunjungan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kunjungan $kunjungan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kunjungan $kunjungan)
    {
        try {
            DB::beginTransaction();

            // Delete related records first
            $kunjungan->kunjunganMuatans()->delete();
            $kunjungan->kunjunganB3s()->delete();
            $kunjungan->delete();

            DB::commit();

            return redirect()->route('kunjungan.index')
                ->with('success', 'Data kunjungan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }
}
