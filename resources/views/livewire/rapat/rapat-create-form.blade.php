<div>
    <div class="d-flex justify-content-center">
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        <form method="POST" wire:submit.prevent="storeRapat" class="col-lg-6 col-md-6 col-sm-10">
            @csrf
            <div class="mb-3">
                <label for="nomor-surat" class="form-label">Nomor Surat :</label>
                <input type="text" wire:model.debounce.250ms="nomorSurat"
                    class="form-control @error('nomor_surat') is-invalid @enderror" id="nomor-surat">
                @error('nomor_surat')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label for="waktu-mulai" class="form-label">Waktu Mulai :</label>
                        <input type="datetime-local" wire:model.debounce.250ms="waktuMulai"
                            class="form-control @error('waktu_mulai') is-invalid @enderror" id="waktu-mulai">
                        @error('waktu_mulai')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="waktu-selesai" class="form-label">Waktu Selesai :</label>
                        <input type="datetime-local" wire:model.debounce.250ms="waktuSelesai"
                            class="form-control @error('waktu_selesai') is-invalid @enderror" id="waktu-mulai">
                        @error('waktu_selesai')
                            <div id="validationServer03Feedback" class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="tempat" class="form-label">Tempat Rapat</label>
                <input type="text" wire:model.debounce.250ms="tempat"
                    class="form-control @error('tempat') is-invalid @enderror" id="tempat">
                @error('tempat')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label>Agenda Rapat :</label>
                <textarea class="form-control @error('agenda_rapat') is-invalid @enderror" placeholder="Agenda Rapat"
                    wire:model.debounce.250ms="agendaRapat"></textarea>
                @error('agenda_rapat')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label>Pilih Kepanitiaan : ( Jika Rapat Merupakan Rapat Kepanitiaan )</label>
                <select class="form-control @error('kepanitiaan') is-invalid @enderror"
                    wire:model.debounce.250ms="kepanitiaanSelected" aria-label="Default select example">
                    <option value="">-- Pilih Kepanitiaan --</option>
                    @foreach ($kepanitiaans as $kepanitiaan)
                        <option value="{{ $kepanitiaan->id }}">{{ $kepanitiaan->nama_kepanitiaan }}
                        </option>
                    @endforeach
                </select>
                @error('kepanitiaan')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3 my-4">
                <label>Pilih Peserta Rapat :</label>
                <div style="max-height: 300px; overflow-y: scroll;">
                    @if ($waktuMulai != null && $waktuSelesai != null)
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
                        </table>
                    @else
                        <h5 class="text-center text-danger">Silahkan Pilih Waktu Mulai dan Waktu Selesai</h5>
                    @endif
                </div>
                @error('peserta_rapat')
                    <span class="text-danger d-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label>Lampiran : ( Jika Ada )</label>
                <input class="form-control" type="file" id="formFile">
            </div>
            <div class="my-3">
                <label>Pilih Pimpinan Rapat :</label>
                <div style="max-height: 300px; overflow-y: scroll;">
                    @if ($pesertaRapat->isNotEmpty())
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
                                                <input class="form-check-input" type="radio"
                                                    wire:model="pimpinanRapat" value="{{ $item['id'] }}">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <h5 class="text-center text-danger">Silahkan Pilih Peserta Rapat Terlebih dahulu</h5>
                    @endif
                </div>
                @error('pimpinan_id')
                    <span class="text-danger d-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3 my-4">
                <label>Pilih Notulis Rapat :</label>
                <div style="max-height: 300px; overflow-y: scroll;">
                    @if ($pesertaRapat->isNotEmpty())
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
                    @else
                        <h5 class="text-center text-danger">Silahkan Pilih Peserta Rapat Terlebih dahulu</h5>
                    @endif
                </div>
                @error('notulis_id')
                    <span class="text-danger d-block">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
