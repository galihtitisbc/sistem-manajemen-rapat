<!-- rapat_pk_direktur.blade.php -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
        }

        .header p {
            font-size: 14px;
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
            <h1>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h1>
            <h1>POLITEKNIK NEGERI BANYUWANGI</h1>
            <p>Jl. Raya Jember kilometer 13 Labanasem, Kabat, Banyuwangi, 68461</p>
            <p>Telepon / Faks : (0333) 636780</p>
            <p>E-mail : poliwangi@poliwangi.ac.id ; Website : http://www.poliwangi.ac.id</p>
        </div>
        <div class="header-line"></div>
        {{-- @php
            echo '<pre>';
            print_r($rapat);
            echo '</pre>';

        @endphp --}}
        <div class="meeting-info">
            <h2>{{ $tanggal ?? 'Apr 24, 2025' }} {{ $rapat['agenda_rapat'] }}</h2>
        </div>

        <div class="meeting-basis">
            <h3 class="heading">Dasar Rapat :</h3>
            <ol>
                @for ($i = 0, $count = count($rapat['rapat_lampiran']); $i < $count; $i++)
                    <li> {{ $rapat['rapat_lampiran'][$i]['nama_file'] }} <a href="">[link]</a>
                    </li>
                @endfor
            </ol>
        </div>

        <div class="attendees">
            <h3 class="heading">Attendees:</h3>
            <ol>
                @for ($i = 0; $i < ($count = count($rapat['rapat_agenda_peserta'])); $i++)
                    <li>Direktur : {{ ucfirst(strtolower($rapat['rapat_agenda_peserta'][$i]['nama'])) }}</li>
                @endfor
            </ol>
        </div>
        <div class="agenda">
            <h3>
                <p class="heading">Agenda Rapat :</p> {{ $rapat['agenda_rapat'] }}
            </h3>
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
                        <li>{{ $rapat['rapat_notulen']['notulen_files'][$i]['nama_file'] }} <a href="#">[link]</a>
                        </li>
                    @endfor
                </ol>
            @endif
            @if ($rapat['rapat_notulen']['catatan'] != null)
                <p> {!! strip_tags($rapat['rapat_notulen']['catatan'], '<b><i><u><p><div><span><ul><ol><li><br>') !!}</p>
            @endif
        </div>
        <div class="documentation">
            <h3>Dokumentasi:</h3>

        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Politeknik Negeri Banyuwangi. All Rights Reserved.</p>
        </div>
    </div>
</body>

</html>
