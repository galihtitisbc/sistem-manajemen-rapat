<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notulensi Rapat Politeknik Negeri Banyuwangi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-color: #0067b3;
            --secondary-color: #ffc107;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --dark-gray: #666;
            --border-radius: 8px;
            --box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0 30px;
            color: var(--text-color);
            background-color: white;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        @page {
            size: A4;
            margin: 2cm 2cm 2cm 2cm;
        }

        /* Header styling */
        .header {
            display: flex;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 30px;
            border-bottom: none;
            position: relative;
        }

        .header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        }

        .header img {
            width: 100px;
            height: auto;
            margin-right: 20px;
        }

        .header-text {
            flex-grow: 1;
        }

        .header h5 {
            color: var(--primary-color);
            font-weight: 700;
            margin: 5px 0;
            text-align: center;
            font-size: 16px;
        }

        .header p {
            text-align: center;
            font-size: 11px;
            margin: 3px 0;
            color: var(--dark-gray);
        }

        /* Meeting title & info */
        .meeting-title {
            background-color: var(--primary-color);
            color: white;
            padding: 15px 20px;
            border-radius: var(--border-radius);
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
        }

        .meeting-title h1 {
            font-size: 20px;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .meeting-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 15px 20px;
            background-color: var(--light-gray);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .meeting-info h2 {
            font-size: 16px;
            margin: 0;
            color: var(--primary-color);
        }

        .meeting-info .meeting-date {
            display: flex;
            align-items: center;
        }

        .meeting-info .meeting-date i {
            margin-right: 8px;
            color: var(--primary-color);
        }

        /* Section styling */
        .section {
            margin-bottom: 30px;
            background-color: white;
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: var(--box-shadow);
            border-left: 4px solid var(--primary-color);
        }

        .section-title {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: var(--primary-color);
            border-bottom: 2px solid var(--secondary-color);
            padding-bottom: 8px;
        }

        .section-title i {
            margin-right: 10px;
            font-size: 18px;
        }

        .section-title h3 {
            font-size: 16px;
            margin: 0;
            font-weight: 600;
        }

        .attendee-card {
            background-color: var(--light-gray);
            padding: 10px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
        }

        .attendee-card i {
            color: var(--primary-color);
            margin-right: 8px;
            font-size: 16px;
        }

        /* Agenda styling */
        .agenda-content {
            background-color: var(--light-gray);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-top: 10px;
        }

        /* Attachments */
        .attachment-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .attachment-item {
            display: flex;
            align-items: center;
            background-color: var(--light-gray);
            padding: 8px 12px;
            border-radius: var(--border-radius);
            width: fit-content;
            transition: all 0.3s ease;
        }

        .attachment-item:hover {
            background-color: #e0e0e0;
        }

        .attachment-item i {
            color: var(--primary-color);
            margin-right: 8px;
        }

        .attachment-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            margin-left: 8px;
        }

        /* Table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table th {
            background-color: var(--primary-color);
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: 500;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        table tr:nth-child(even) {
            background-color: var(--light-gray);
        }

        table tr:hover {
            background-color: #e8f4fd;
        }

        /* Status badges */
        .status-badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .status-pending {
            background-color: #f8d7da;
            color: red;
        }

        .status-complete {
            background-color: #d4edda;
            color: #155724;
        }

        .status-progress {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        /* Documentation */
        .documentation-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .gallery-item {
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--box-shadow);
            transition: transform 0.3s ease;
        }

        .gallery-item:hover {
            transform: scale(1.02);
        }

        .gallery-item img {
            width: 100%;
            height: auto;
            display: block;
        }

        /* Footer styling */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: var(--dark-gray);
            padding: 20px 0;
            border-top: 1px solid #e0e0e0;
        }

        /* Notes */
        .notes-content {
            background-color: var(--light-gray);
            padding: 15px;
            border-radius: var(--border-radius);
            margin-top: 10px;
            line-height: 1.7;
        }

        /* Icons */
        .material-icons {
            font-family: 'Material Icons';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }

        .btn-print {
            margin-top: 30px;
            display: inline-block;
            padding: 5px 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 9pt;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
            transition: all 0.3s ease;
        }

        /* Tombol Print hanya muncul di web */
        .web-only {
            text-align: center;
            margin-bottom: 20px;
        }

        @media print {
            .web-only {
                display: none !important;
            }

            .meeting-title {
                background-color: var(--primary-color) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .container {
                width: 100%;
                max-width: 100%;
                margin: 0 auto;
                padding: 0 1cm;
                box-sizing: border-box;
            }
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body>
    <div class="container">
        <!-- Tombol Print Preview -->
        <div class="web-only">
            <button class="btn-print" onclick="window.print()">🖨 Tampilkan Print Preview</button>
        </div>

        <div class="header">
            <img src="{{ asset('assets/img/pdf/Logo_Politeknik_Negeri_Banyuwangi.png') }}"
                alt="Logo Politeknik Negeri Banyuwangi">
            <div class="header-text">
                <h5>KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h5>
                <h5>POLITEKNIK NEGERI BANYUWANGI</h5>
                <p>Jl. Raya Jember kilometer 13 Labanasem, Kabat, Banyuwangi, 68461</p>
                <p>Telepon / Faks : (0333) 636780</p>
                <p>E-mail : poliwangi@poliwangi.ac.id ; Website : http://www.poliwangi.ac.id</p>
            </div>
        </div>

        @php
            use Carbon\Carbon;
            use Modules\Rapat\Http\Helper\StatusTindakLanjut;

            Carbon::setLocale('id');
            $waktuMulai = Carbon::parse($rapat['waktu_mulai'])->translatedFormat('l, d F Y H:i');
        @endphp

        <div class="meeting-title">
            <h1>NOTULENSI RAPAT</h1>
        </div>

        <div class="meeting-info">
            <div class="meeting-date">
                <i class="fas fa-calendar-alt"></i>
                <h2>{{ $waktuMulai }}</h2>
            </div>
        </div>

        <div class="section">
            <div class="section-title">
                <i class="fas fa-calendar-check"></i>
                <h3>Agenda Rapat</h3>
            </div>
            <div class="agenda-content">
                <p>{{ $rapat['agenda_rapat'] }}</p>
            </div>
        </div>

        <div class="section">
            <div class="section-title">
                <i class="fas fa-users"></i>
                <h3>Daftar Hadir</h3>
            </div>
            <div class="attendees-grid">

                <div class="row">
                    @for ($i = 0; $i < ($count = count($rapat['rapat_agenda_peserta'])); $i++)
                        <div class="col-6 my-2">
                            <div class="attendee-card">
                                <i class="fas fa-user"></i>
                                {{ ucwords(strtolower($rapat['rapat_agenda_peserta'][$i]['nama'])) }}
                            </div>
                        </div>
                    @endfor
                </div>

            </div>
        </div>

        <div class="section">
            <div class="section-title">
                <i class="fas fa-user-edit"></i>
                <h3>Notulis</h3>
            </div>
            <div class="attendee-card">
                <i class="fas fa-pen"></i>
                {{ ucwords(strtolower($rapat['rapat_agenda_notulis']['nama'])) }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">
                <i class="fas fa-paperclip"></i>
                <h3>Lampiran Rapat</h3>
            </div>
            <div class="attachment-list">
                @for ($i = 0, $count = count($rapat['rapat_lampiran']); $i < $count; $i++)
                    <div class="attachment-item">
                        <i class="fas fa-file-alt"></i>
                        <a
                            href="{{ url('/rapat/agenda-rapat/' . $rapat['rapat_lampiran'][$i]['nama_file'] . '/download') }}">
                            {{ $rapat['rapat_lampiran'][$i]['nama_file'] }}
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                @endfor
            </div>
        </div>

        <div class="section">
            <div class="section-title">
                <i class="fas fa-file-alt"></i>
                <h3>Notulen Rapat</h3>
            </div>
            @if (count($rapat['rapat_notulen']['notulen_files']) > 0)
                <div class="attachment-list">
                    @for ($i = 0, $count = count($rapat['rapat_notulen']['notulen_files']); $i < $count; $i++)
                        <div class="attachment-item">
                            <i class="fas fa-file-word"></i>
                            <a
                                href="{{ url('/rapat/agenda-rapat/notulis/' . $rapat['rapat_notulen']['notulen_files'][$i]['nama_file'] . '/download') }}">
                                {{ $rapat['rapat_notulen']['notulen_files'][$i]['nama_file'] }}
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    @endfor
                </div>
            @endif

            @if ($rapat['rapat_notulen']['catatan'] != null)
                <div class="notes-content">
                    {!! strip_tags($rapat['rapat_notulen']['catatan'], '<b><strong><i><u><p><div><span><ul><ol><li><br>') !!}
                </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">
                <i class="fas fa-tasks"></i>
                <h3>Penugasan Tindak Lanjut Rapat</h3>
            </div>
            @if (count($rapat['rapat_tindak_lanjut']) > 0)
                <table>
                    <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th style="width: 25%">Nama</th>
                            <th style="width: 50%">Tugas</th>
                            <th style="width: 20%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < ($count = count($rapat['rapat_tindak_lanjut'])); $i++)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ ucwords(strtolower($rapat['rapat_tindak_lanjut'][$i]['pegawai']['nama'])) }}
                                </td>
                                <td>{{ $rapat['rapat_tindak_lanjut'][$i]['deskripsi_tugas'] }}</td>
                                <td>
                                    @php
                                        $status = StatusTindakLanjut::from($rapat['rapat_tindak_lanjut'][$i]['status'])
                                            ->value;
                                        $statusClass = '';
                                        if ($status == StatusTindakLanjut::BELUM_SELESAI->value) {
                                            $statusClass = 'status-pending';
                                        } else {
                                            $statusClass = 'status-complete';
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ StatusTindakLanjut::from($rapat['rapat_tindak_lanjut'][$i]['status'])->label() }}
                                    </span>
                                </td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            @else
                <div class="empty-state">
                    <p><i class="fas fa-info-circle"></i> Tidak Ada Tugas</p>
                </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">
                <i class="fas fa-images"></i>
                <h3>Dokumentasi Rapat</h3>
            </div>
            <div class="documentation-gallery">
                @foreach ($rapat['rapat_dokumentasi'] as $dok)
                    <div class="gallery-item">
                        <img src="{{ asset('/storage/dokumentasi-rapat/' . $dok['foto'] . '') }}"
                            alt="Dokumentasi Rapat">
                    </div>
                @endforeach
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Politeknik Negeri Banyuwangi. All Rights Reserved.</p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
</body>

</html>
