<?php

namespace Modules\Rapat\Http\Controllers;

use App\Models\Core\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Rapat\Entities\Kepanitiaan;
use Modules\Rapat\Http\Helper\FlashMessage;
use Modules\Rapat\Http\Requests\KepanitiaanRequest;

class KepegawaianController extends Controller
{
    public function index()
    {
        $kepanitiaans = Kepanitiaan::with('users')->get();
        return view('rapat::kepegawaian.index', [
            'kepanitiaans' => $kepanitiaans
        ]);
    }
    public function create()
    {
        $users = User::all();
        return view('rapat::kepegawaian.create', [
            'users' => $users
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
        $users = User::all();
        $selectedUsers = $kepanitiaan->users->pluck('id')->toArray();
        return view('rapat::kepegawaian.edit', [
            'kepanitiaan' => $kepanitiaan,
            'users' => $users,
            'selectedUsers' => $selectedUsers
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
