<div>
    <div class="d-flex justify-content-center">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" wire:submit.prevent="storeRapat" class="col-lg-6 col-md-6 col-sm-10">
            @csrf
            <div class="mb-3">
                <label for="judul-rapat" class="form-label">Judul Rapat</label>
                <input type="text" wire:model.debounce.500ms="judulRapat" class="form-control" id="judul-rapat">

            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label for="waktu-mulai" class="form-label">Waktu Mulai :</label>
                        <input type="datetime-local" wire:model.debounce.500ms="waktuMulai" class="form-control"
                            id="waktu-mulai">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="waktu-selesai" class="form-label">Waktu Selesai :</label>
                        <input type="datetime-local" wire:model.debounce.500ms="waktuSelesai" class="form-control"
                            id="waktu-mulai">
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="tempat" class="form-label">Tempat Rapat</label>
                <input type="text" wire:model.debounce.500ms="tempat" class="form-control" id="tempat">
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea class="form-control" placeholder="Deskripsi Rapat" wire:model.debounce.500ms="deskripsi"></textarea>
            </div>
            <div class="mb-3">
                <label>Pilih Kepanitiaan : ( Jika Rapat Merupakan Rapat Kepanitiaan )</label>
                <select class="form-control" wire:model.debounce.500ms="kepanitiaanSelected"
                    aria-label="Default select example">
                    <option value="">-- Pilih Kepanitiaan --</option>
                    @foreach ($kepanitiaans as $kepanitiaan)
                        <option value="{{ $kepanitiaan->id }}">{{ $kepanitiaan->nama_kepanitiaan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label>Pilih Peserta Rapat :</label>
                <div style="max-height: 300px; overflow-y: scroll;">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Undang</th>
                            </tr>
                        </thead>
                        @if ($waktuMulai != null && $waktuSelesai != null)
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>TRPL</td>
                                        <td>
                                            @if (
                                                $user->rapatAgendaPeserta->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])->isNotEmpty() ||
                                                    $user->rapatAgendaPeserta->whereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])->isNotEmpty())
                                                <strong>Peserta Rapat Tidak Bisa Di Undang</strong>
                                            @else
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        value="{{ $user->id }}" id="flexCheckDefault"
                                                        wire:click="selectPesertaRapat({{ $user }},$event.target.checked)">
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        @else
                            <h5>Silahkan Pilih Waktu Mulai dan Waktu Selesai</h5>
                        @endif
                    </table>
                </div>
            </div>
            <div class="mb-3">
                <label>Lampiran : ( Jika Ada )</label>
                <input class="form-control" type="file" id="formFile">
            </div>
            <div class="my-3">
                <label>Pilih Pimpinan Rapat :</label>
                <div style="max-height: 300px; overflow-y: scroll;">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Pilih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesertaRapat as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>TRPL</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" wire:model="pimpinanRapat"
                                                value="{{ $item['id'] }}">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mb-3">
                <label>Pilih Notulis Rapat :</label>
                <div style="max-height: 300px; overflow-y: scroll;">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th>Nama</th>
                                <th>Prodi</th>
                                <th>Undang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pesertaRapat as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item['name'] }}</td>
                                    <td>TRPL</td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" wire:model="notulisRapat"
                                                value="{{ $item['id'] }}">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
