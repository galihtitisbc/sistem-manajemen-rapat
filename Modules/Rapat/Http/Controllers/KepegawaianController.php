<?php
namespace Modules\Rapat\Http\Controllers;

use App\Models\Core\User;
use Illuminate\Routing\Controller;
use Modules\Rapat\Entities\Kepanitiaan;
use Modules\Rapat\Entities\Pegawai;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Requests\KepanitiaanRequest;

class KepegawaianController extends Controller
{
    public function index()
    {
        $kepanitiaans = Kepanitiaan::with('pegawai')->get();
        return view('rapat::kepegawaian.index', [
            'kepanitiaans' => $kepanitiaans,
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
        $users = User::all();
        return view('rapat::kepegawaian.create', [
            'users' => $users,
        ]);
    }

    public function store(KepanitiaanRequest $request)
    {
        try {
            $kepanitiaan = Kepanitiaan::create($request->validated());
            $kepanitiaan->users()->attach($request->user_ids);
            FlashMessage::success('Kepanitiaan Berhasil Di Tambahkan');
            return redirect()->to('/rapat/panitia');
        } catch (\Throwable $th) {
            FlashMessage::error('Kepanitiaan Gagal Di Tambahkan');
            return redirect()->to('/rapat/panitia');
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
}
