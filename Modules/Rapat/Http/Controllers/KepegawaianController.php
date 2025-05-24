<?php
namespace Modules\Rapat\Http\Controllers;

use App\Models\Core\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Rapat\Entities\Kepanitiaan;
use Modules\Rapat\Entities\Pegawai;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Helper\RoleGroupHelper;
use Modules\Rapat\Http\Requests\KepanitiaanRequest;
use Modules\Rapat\Http\Requests\UpdateKepanitiaanRequest;
use Modules\Rapat\Jobs\WhatsappSenderKepanitiaan;

class KepegawaianController extends Controller
{
    public function index()
    {
        $kepanitiaans = '';
        if (RoleGroupHelper::userHasRoleGroup(Auth::user(), RoleGroupHelper::kepegawaianRoles())) {
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
            $validated   = $request->validated();
            $kepanitiaan = Kepanitiaan::create($validated);
            $kepanitiaan->pegawai()->attach($validated['peserta_panitia']);
            WhatsappSenderKepanitiaan::dispatch($kepanitiaan, 'create');
            return response()->json(['message' => 'Kepanitiaan berhasil ditambahkan.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Gagal menambahkan kepanitiaan.']);
        }
    }

    public function edit(Kepanitiaan $kepanitiaan)
    {
        $kepanitiaan->load('pegawai');
        $selectedPegawai = $kepanitiaan->pegawai->pluck('username')->toArray();
        return view('rapat::kepegawaian.edit', [
            'kepanitiaan'     => $kepanitiaan,
            'selectedPegawai' => $selectedPegawai,
        ]);
    }

    public function update(UpdateKepanitiaanRequest $request, Kepanitiaan $kepanitiaan)
    {
        try {
            $validated = $request->validated();
            $kepanitiaan->update($validated);
            $kepanitiaan->pegawai()->sync($validated['peserta_panitia']);
            WhatsappSenderKepanitiaan::dispatch($kepanitiaan, 'update');
            return response()->json(['message' => 'Kepanitiaan berhasil diubah.']);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Gagal Mengubah kepanitiaan.']);
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
    public function download(Kepanitiaan $kepanitiaan)
    {
        return $kepanitiaan;
    }
}
