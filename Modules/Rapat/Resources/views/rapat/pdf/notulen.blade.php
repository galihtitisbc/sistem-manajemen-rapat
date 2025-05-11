<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        @page {
            size: A4;
            margin: 2cm 2cm 2cm 2cm;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
            text-align: left;
        }

        .header img {
            width: 100px;
            margin-bottom: -200px;
            margin-left: 10px;
            height: auto;
        }

        .header .header-text {
            margin-top: -50px;
            margin-right: -50px;
        }

        .header h5 {
            text-align: center;
            font-weight: bold;
            margin: 5px 0;
        }

        .header p {
            text-align: center;
            font-size: 10px;
            margin: 5px 0;
        }

        .header-line {
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 20px;
        }

        .meeting-info {
            margin-bottom: 20px;
        }

        .meeting-info h2 {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .meeting-basis {
            margin-bottom: 20px;
        }

        .meeting-basis h3 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .meeting-basis ol {
            margin-left: 20px;
        }

        .attendees {
            margin-bottom: 20px;
        }

        .attendees h3 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .attendees ol {
            margin-left: 20px;
        }

        .agenda {
            margin-bottom: 20px;
        }

        .agenda h3 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .agenda ol {
            margin-left: 20px;
        }

        .content {
            margin-bottom: 20px;
        }

        .content h3 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .content ul {
            margin-left: 20px;
            list-style-type: circle;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ddd;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .documentation {
            margin-top: 30px;
        }

        .documentation h3 {
            font-size: 14px;
            margin-bottom: 15px;
        }

        .documentation .images {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
        }

        .documentation .images img {
            max-width: 300px;
            height: auto;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .heading {
            padding: 3px;
            background-color: yellow;
            display: inline-block;
            width: auto;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ $logo }}" alt="Logo">
            <div class="header-text">
                <h5>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h5>
                <h5>POLITEKNIK NEGERI BANYUWANGI</h5>
                <p>Jl. Raya Jember kilometer 13 Labanasem, Kabat, Banyuwangi, 68461</p>
                <p>Telepon / Faks : (0333) 636780</p>
                <p>E-mail : poliwangi@poliwangi.ac.id ; Website : http://www.poliwangi.ac.id</p>
            </div>
        </div>
        <div class="header-line"></div>
        @php
            use Carbon\Carbon;
            Carbon::setLocale('id');
        @endphp
        @php
            // echo '<pre>';
            // print_r($rapat);
            // echo '</pre>';
            $waktuMulai = Carbon::parse($rapat['waktu_mulai'])->translatedFormat('l, d F Y H:i');
        @endphp
        <div class="meeting-info">
            <h2>{{ $waktuMulai }}</h2>
        </div>

        <div class="meeting-basis">
            <h3 class="heading">Lampiran Rapat :</h3>
            <ol>
                @for ($i = 0, $count = count($rapat['rapat_lampiran']); $i < $count; $i++)
                    <li> {{ $rapat['rapat_lampiran'][$i]['nama_file'] }} <a
                            href="{{ url('/rapat/agenda-rapat/' . $rapat['rapat_lampiran'][$i]['nama_file'] . '/download') }}">[link]</a>
                    </li>
                @endfor
            </ol>
        </div>

        <div class="attendees">
            <h3 class="heading">Daftar Hadir:</h3>
            <ol>
                @for ($i = 0; $i < ($count = count($rapat['rapat_agenda_peserta'])); $i++)
                    <li>{{ ucfirst(strtolower($rapat['rapat_agenda_peserta'][$i]['nama'])) }}</li>
                @endfor
            </ol>
        </div>
        <div class="agenda">
            <h3 class="heading">Agenda Rapat :</h3>
            <p>{{ $rapat['agenda_rapat'] }}</p>
        </div>
        <div class="attendees">
            <h3 class="heading">Notulis :</h3>
            <ul>
                <li>
                    {{ ucfirst(strtolower($rapat['rapat_agenda_notulis']['nama'])) }}
                </li>
            </ul>
        </div>
        <div class="agenda">
            <h3 class="heading">Notulen Rapat</h3>
            @if (count($rapat['rapat_notulen']['notulen_files']) > 0)
                <ol>
                    @for ($i = 0, $count = count($rapat['rapat_notulen']['notulen_files']); $i < $count; $i++)
                        <li>{{ $rapat['rapat_notulen']['notulen_files'][$i]['nama_file'] }} <a
                                href="{{ url('/rapat/agenda-rapat/notulis/' . $rapat['rapat_notulen']['notulen_files'][$i]['nama_file'] . '/download') }}">[link]</a>
                        </li>
                    @endfor
                </ol>
            @endif
            @if ($rapat['rapat_notulen']['catatan'] != null)
                <p> {!! strip_tags($rapat['rapat_notulen']['catatan'], '<b><strong><i><u><p><div><span><ul><ol><li><br>') !!}</p>
            @endif
        </div>
        <div class="attendees">
            <h3 class="heading">Penugasan Tindak Lanjut Rapat</h3>
            <table>
                <thead>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Tugas</th>
                    <th>Status</th>
                </thead>
                <tbody>
                    @if (count($rapat['rapat_tindak_lanjut']) > 0)
                        @for ($i = 0; $i < ($count = count($rapat['rapat_tindak_lanjut'])); $i++)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ ucfirst(strtolower($rapat['rapat_tindak_lanjut'][$i]['pegawai']['nama'])) }}
                                </td>
                                <td>{{ $rapat['rapat_tindak_lanjut'][$i]['deskripsi_tugas'] }}</td>
                                <td>{{ $rapat['rapat_tindak_lanjut'][$i]['status'] }}</td>
                            </tr>
                        @endfor
                    @else
                        <h3>Tidak Ada Tugas</h3>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="documentation">
            <h3 class="heading">Dokumentasi Rapat:</h3>
            <br>
            @foreach ($rapat['rapat_dokumentasi'] as $dok)
                @if (!empty($dok['foto_base64']))
                    <img width="400" height="300" src="{{ $dok['foto_base64'] }}" alt="dokumentasi">
                    <br>
                @else
                    <p>Gagal memuat gambar</p>
                @endif
            @endforeach
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Politeknik Negeri Banyuwangi. All Rights Reserved.</p>
        </div>
    </div>
</body>

</html>
