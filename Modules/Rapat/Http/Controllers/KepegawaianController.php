<?php
namespace Modules\Rapat\Http\Controllers;

use App\Models\Core\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Modules\Rapat\Entities\Kepanitiaan;
use Modules\Rapat\Entities\Pegawai;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Requests\KepanitiaanRequest;

class KepegawaianController extends Controller
{
    public function index()
    {
        $kepanitiaans = '';
        if (in_array('kepegawaian', Auth::user()->roles->pluck('name')->toArray())) {
            $kepanitiaans = Kepanitiaan::with('pegawai')->get();
        } else {
            $kepanitiaans = Kepanitiaan::pegawaiIsAnggotaPanitia(Auth::user()->pegawai->username)->with('pegawai')->get();
        }
        return view('rapat::kepegawaian.index', [
            'kepanitiaans' => $kepanitiaans,
        ]);
    }
    public function detail(Kepanitiaan $kepanitiaan)
    {
        $kepanitiaan->load('ketua');
        return view('rapat::kepegawaian.detail', [
            'panitia' => $kepanitiaan,
        ]);
    }
    public function ajaxKepanitiaanRapat($id)
    {
        try {
            $kepanitiaan = Kepanitiaan::with('pegawai')->where('id', $id)->firstOrFail();
            return response()->json($kepanitiaan);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Kepanitiaan Tidak Ditemukan',
            ], 404);
        }
    }
    public function create()
    {
        $pegawais = Pegawai::all();
        return view('rapat::kepegawaian.create', [
            'pegawais' => $pegawais,
        ]);
    }

    public function store(KepanitiaanRequest $request)
    {
        try {
            $validated = $request->validated();
            if (isset($validated['surat_tugas'])) {
                // Simpan surat tugas ke storage
                $file     = $validated['surat_tugas'];
                $fileName = time() . '_' . $file->getClientOriginalName();
                Storage::putFileAs('public/kepanitiaan', $file, $fileName);
                $validated['surat_tugas'] = $fileName;
            }
            $kepanitiaan = Kepanitiaan::create($validated);
            $kepanitiaan->pegawai()->attach($validated['peserta_panitia']);
            return response()->json(['message' => 'Kepanitiaan berhasil ditambahkan.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Gagal menambahkan kepanitiaan.']);
        }
    }

    public function edit(Kepanitiaan $kepanitiaan)
    {
        $pegawais        = Pegawai::all();
        $selectedPegawai = $kepanitiaan->pegawai->pluck('username')->toArray();
        return view('rapat::kepegawaian.edit', [
            'kepanitiaan'     => $kepanitiaan,
            'pegawais'        => $pegawais,
            'selectedPegawai' => $selectedPegawai,
        ]);
    }

    public function update(KepanitiaanRequest $request, Kepanitiaan $kepanitiaan)
    {
        try {
            $kepanitiaan->update($request->validated());
            $kepanitiaan->users()->sync($request->user_ids);
            FlashMessage::success('Kepanitiaan Berhasil Di Diubah');
            return redirect()->to('/rapat/panitia');
        } catch (\Throwable $th) {
            FlashMessage::error('Kepanitiaan Gagal Di Ubah');
            return redirect()->to('/rapat/panitia');
        }
    }

    public function changeStatus(Kepanitiaan $kepanitiaan)
    {
        try {
            $kepanitiaan->update([
                'status' => $kepanitiaan->status == 'AKTIF' ? 'NON_AKTIF' : 'AKTIF',
            ]);
            FlashMessage::success('Status Kepanitiaan Berhasil Di Diubah');
            return redirect()->to('/rapat/panitia');
        } catch (\Throwable $th) {
            FlashMessage::error('Status Kepanitiaan Gagal Di Ubah');
            return redirect()->to('/rapat/panitia');
        }
    }
    public function download($file)
    {
        return Storage::download('public/kepanitiaan/' . $file);
    }
}
