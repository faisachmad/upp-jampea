<?php

namespace App\Http\Controllers;

use App\Http\Requests\KunjunganRequest;
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
        $query = Kunjungan::with(['pelabuhan', 'kapal', 'jenisPelayaran', 'nakhoda', 'pelabuhanAsal', 'pelabuhanTujuan']);

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

        $kunjungans = $query->orderBy('tgl_tiba', 'desc')->paginate(20);

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
        return view('kunjungan.create', $this->formData());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KunjunganRequest $request)
    {
        try {
            $kunjungan = DB::transaction(fn () => $this->persistKunjungan($request));

            return redirect()->route('kunjungan.index')
                ->with('success', 'Data kunjungan kapal berhasil disimpan.');

        } catch (\Exception $e) {
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
            'pelabuhanAsal', 'pelabuhanTujuan', 'muatans', 'b3s.barangB3']);

        return view('kunjungan.show', compact('kunjungan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kunjungan $kunjungan)
    {
        $kunjungan->load(['muatans', 'b3s']);

        return view('kunjungan.edit', array_merge($this->formData(), [
            'kunjungan' => $kunjungan,
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KunjunganRequest $request, Kunjungan $kunjungan)
    {
        try {
            DB::transaction(fn () => $this->persistKunjungan($request, $kunjungan));

            return redirect()->route('kunjungan.show', $kunjungan)
                ->with('success', 'Data kunjungan kapal berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kunjungan $kunjungan)
    {
        try {
            DB::beginTransaction();

            // Delete related records first
            $kunjungan->muatans()->delete();
            $kunjungan->b3s()->delete();
            $kunjungan->delete();

            DB::commit();

            return redirect()->route('kunjungan.index')
                ->with('success', 'Data kunjungan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    private function formData(): array
    {
        return [
            'pelabuhans' => Pelabuhan::active()->orderBy('kode')->get(),
            'jenisPelayarans' => JenisPelayaran::orderBy('kode')->get(),
            'nakhodas' => Nakhoda::active()->orderBy('nama')->get(),
            'kapals' => Kapal::active()->orderBy('nama')->get(),
            'barangB3s' => BarangB3::orderBy('nama')->get(),
            'jenisKapals' => JenisKapal::active()->orderBy('nama')->get(),
            'benderas' => Bendera::active()->orderBy('nama_negara')->get(),
        ];
    }

    private function persistKunjungan(KunjunganRequest $request, ?Kunjungan $kunjungan = null): Kunjungan
    {
        $validated = $request->validated();

        $penumpangTurun = ($validated['pnp_datang_dewasa'] ?? 0) + ($validated['pnp_datang_anak'] ?? 0);
        $penumpangNaik = ($validated['pnp_tolak_dewasa'] ?? 0) + ($validated['pnp_tolak_anak'] ?? 0);
        $motorTurun = $validated['kend_datang_gol1'] ?? 0;
        $motorNaik = $validated['kend_tolak_gol1'] ?? 0;
        $mobilTurun = ($validated['kend_datang_gol2'] ?? 0) + ($validated['kend_datang_gol3'] ?? 0) + ($validated['kend_datang_gol4a'] ?? 0) + ($validated['kend_datang_gol4b'] ?? 0) + ($validated['kend_datang_gol5'] ?? 0);
        $mobilNaik = ($validated['kend_tolak_gol2'] ?? 0) + ($validated['kend_tolak_gol3'] ?? 0) + ($validated['kend_tolak_gol4a'] ?? 0) + ($validated['kend_tolak_gol4b'] ?? 0) + ($validated['kend_tolak_gol5'] ?? 0);

        $payload = [
            'pelabuhan_id' => $validated['pelabuhan_id'],
            'kapal_id' => $validated['kapal_id'],
            'jenis_pelayaran_id' => $validated['jenis_pelayaran_id'],
            'nakhoda_id' => $validated['nakhoda_id'] ?? null,
            'bulan' => $validated['bulan'],
            'tahun' => $validated['tahun'],
            'tgl_tiba' => $validated['tgl_datang'],
            'jam_tiba' => $validated['jam_datang'],
            'pelabuhan_asal_id' => $validated['pelabuhan_asal_id'],
            'status_muatan_tiba' => $validated['status_muatan_tiba'] ?? null,
            'tgl_tambat' => $validated['tgl_tambat'] ?? null,
            'jam_tambat' => $validated['jam_tambat'] ?? null,
            'tgl_berangkat' => $validated['tgl_tolak'],
            'jam_berangkat' => $validated['jam_tolak'],
            'pelabuhan_tujuan_id' => $validated['pelabuhan_tujuan_id'],
            'status_muatan_tolak' => $validated['status_muatan_tolak'] ?? null,
            'no_spb_tiba' => $validated['no_spb_datang'] ?? null,
            'no_spb_tolak' => $validated['no_spb_tolak'] ?? null,
            'eta' => $validated['eta'] ?? null,
            'pnp_datang_dewasa' => $validated['pnp_datang_dewasa'] ?? 0,
            'pnp_datang_anak' => $validated['pnp_datang_anak'] ?? 0,
            'pnp_tolak_dewasa' => $validated['pnp_tolak_dewasa'] ?? 0,
            'pnp_tolak_anak' => $validated['pnp_tolak_anak'] ?? 0,
            'penumpang_turun' => $penumpangTurun,
            'penumpang_naik' => $penumpangNaik,
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
            'motor_turun' => $motorTurun,
            'motor_naik' => $motorNaik,
            'mobil_turun' => $mobilTurun,
            'mobil_naik' => $mobilNaik,
            'lanjutan_jenis' => $validated['lanjutan_jenis'] ?? null,
            'lanjutan_ton' => $validated['lanjutan_ton'] ?? 0,
            'lanjutan_mobil' => $validated['lanjutan_mobil'] ?? 0,
            'lanjutan_motor' => $validated['lanjutan_motor'] ?? 0,
            'lanjutan_penumpang' => $validated['lanjutan_penumpang'] ?? 0,
        ];

        if ($kunjungan === null) {
            $kunjungan = Kunjungan::create($payload);
        } else {
            $kunjungan->update($payload);
            $kunjungan->muatans()->delete();
            $kunjungan->b3s()->delete();
        }

        foreach ($validated['muatan'] ?? [] as $muatan) {
            KunjunganMuatan::create([
                'kunjungan_id' => $kunjungan->id,
                'tipe' => $muatan['tipe'],
                'jenis_barang' => $muatan['jenis_barang'],
                'ton_m3' => $muatan['ton_m3'] ?? null,
                'jenis_hewan' => $muatan['jenis_hewan'] ?? null,
                'jumlah_hewan' => $muatan['jumlah_hewan'] ?? 0,
            ]);
        }

        foreach ($validated['b3'] ?? [] as $b3) {
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

        return $kunjungan->fresh(['muatans', 'b3s']);
    }
}
