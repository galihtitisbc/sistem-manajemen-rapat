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
        <form wire:submit.prevent="updateRapat" class="col-lg-8 col-md-6 col-sm-10" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="nomor-surat" class="form-label">Nomor Surat Undangan :</label>
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
                <select class="form-control @error('tempat') is-invalid @enderror" wire:model="selectTempat">
                    <option selected value="">-- Pilih Tempat --</option>
                    <option value="zoom">Online</option>
                    <option value="custom">Tempat Lain</option>
                </select>
                @error('tempat')
                    <div id="validationServer03Feedback" class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                @if ($selectTempat === 'custom')
                    <div class="mt-3">
                        <label for="customInput" class="form-label">Masukkan Tempat Rapat</label>
                        <input type="text" id="customInput"
                            class="form-control @error('tempat') is-invalid @enderror" wire:model="customTempat"
                            placeholder="Masukkan Tempat Rapat">
                    </div>
                    @error('tempat')
                        <div id="validationServer03Feedback" class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                @endif
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
                    wire:change="setSelectedKepanitiaan($event.target.value)">
                    <option value="">-- Pilih Kepanitiaan --</option>
                    @foreach ($kepanitiaans as $kepanitiaan)
                        <option value="{{ $kepanitiaan->id }}"
                            {{ $kepanitiaan->id == $selectedKepanitiaan ? 'selected' : '' }}>
                            {{ $kepanitiaan->nama_kepanitiaan }}
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
                <div class="d-flex justify-content-between">
                    <label>Pilih Peserta Rapat :</label>
                    <input type="text" class="form-control col-lg-5 col-sm-auto col-md-auto"
                        placeholder="Cari Peserta" wire:model="cariPeserta">
                </div>
                <div style="max-height: 300px; overflow-y: scroll;" class="my-4">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th>Nama</th>
                                <th>Whatsapp</th>
                                <th>Undang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                </h5>
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>0822123</td>
                                    <td>
                                        @if (
                                            !$pesertaRapat->contains('id', $user->id) &&
                                                ($user->rapatAgendaPeserta->whereBetween('waktu_mulai', [$waktuMulai, $waktuSelesai])->isNotEmpty() ||
                                                    $user->rapatAgendaPeserta->whereBetween('waktu_selesai', [$waktuMulai, $waktuSelesai])->isNotEmpty()))
                                            <strong>Peserta Ada Rapat Yang Lain</strong>
                                        @else
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    value="{{ $user->id }}" id="flexCheckDefault"
                                                    wire:change="selectPesertaRapat({{ $user }},$event.target.checked)"
                                                    {{ $pesertaRapat->contains('id', $user->id) ? 'checked' : '' }}>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @error('peserta_rapat')
                    <span class="text-danger d-block">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-3">
                <label>Lampiran : ( Jika Ada )</label>
                <input type="file" class="form-control" id="lampiran-file" wire:model="lampiran" multiple>
                <div wire:loading wire:target="lampiran" class="mt-2 text-blue-500 font-semibold animate-pulse">
                    Sedang Upload File ....
                </div>
                @error('lampiran.*')
                    <span class="text-danger d-block">{{ $message }}</span>
                @enderror
                @php
                    $icons = [
                        'jpg' => ['icon' => 'fas fa-file-image', 'color' => '#FFD700'],
                        'jpeg' => ['icon' => 'fas fa-file-image', 'color' => '#FFD700'],
                        'png' => ['icon' => 'fas fa-file-image', 'color' => '#FFD700'],
                        'doc' => ['icon' => 'fas fa-file-word', 'color' => '#1E90FF'],
                        'docx' => ['icon' => 'fas fa-file-word', 'color' => '#1E90FF'],
                        'xls' => ['icon' => 'fas fa-file-excel', 'color' => '#008000'],
                        'xlsx' => ['icon' => 'fas fa-file-excel', 'color' => '#008000'],
                        'pdf' => ['icon' => 'fas fa-file-pdf', 'color' => '#FF0000'],
                        'txt' => ['icon' => 'fas fa-file-alt', 'color' => '#808080'],
                    ];
                @endphp
                @if (!$lampiran)
                    @foreach ($lampiranOld as $item)
                        <i class="{{ $icons['pdf']['icon'] }}"
                            style="color: {{ $icons['pdf']['color'] }}; fa-lg  mr-2"></i>
                        {{ $item }}
                        <br>
                    @endforeach
                @endif
                {{-- File Preview --}}
                @if ($lampiran)
                    <div class="mt-3">
                        @foreach ($lampiran as $item)
                            @php
                                $extension = strtolower($item->getClientOriginalExtension());
                                $fileData = $icons[$extension] ?? ['icon' => 'fas fa-file', 'color' => '#A9A9A9'];
                            @endphp
                            <i class="{{ $fileData['icon'] }}"
                                style="color: {{ $fileData['color'] }}; fa-lg  mr-2"></i>
                            {{ $item->getClientOriginalName() }}
                            <br>
                        @endforeach
                    </div>
                @endif

            </div>
            <div class="my-4">
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
                                @foreach ($pesertaRapat->sortByDesc(fn($item, $key) => $key)->values() as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td>TRPL</td>
                                        <td>
                                            @if ($item['id'] != $notulisRapat)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        wire:model="pimpinanRapat" value="{{ $item['id'] }}"
                                                        {{ $item['id'] == $pimpinanRapat ? 'checked' : '' }}>
                                                </div>
                                            @endif
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
                                @foreach ($pesertaRapat->sortByDesc(fn($item, $key) => $key)->values() as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['name'] }}</td>
                                        <td>TRPL</td>
                                        <td>
                                            @if ($item['id'] != $pimpinanRapat)
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio"
                                                        wire:model="notulisRapat" value="{{ $item['id'] }}"
                                                        {{ $item['id'] == $notulisRapat ? 'checked' : '' }}>
                                                </div>
                                            @endif
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
            <div class="text-center">
                <button type="submit" wire:loading.remove wire:target="storeRapat,lampiran"
                    class="btn btn-warning mx-auto">Update</button>
            </div>
            <div wire:loading wire:target="storeRapat" class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </form>
    </div>
</div>
