<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Tugas Kepanitiaan</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background: white;
        }

        .container {
            width: 100%;
            max-width: 210mm;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }

        .kop-surat {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #000;
        }

        .kop-surat img {
            width: 80px;
            height: 80px;
            margin-right: 20px;
            object-fit: contain;
        }

        .kop-surat-text {
            flex: 1;
            text-align: center;
        }

        .kop-surat-text strong {
            font-weight: bold;
            font-size: 14pt;
        }

        .nomor-surat {
            text-align: center;
            margin: 30px 0;
            font-size: 14pt;
        }

        .judul-surat {
            text-align: center;
            font-weight: bold;
            font-size: 16pt;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .nomor-sk {
            text-align: center;
            margin-bottom: 30px;
            font-size: 12pt;
        }

        .content {
            margin: 30px 0;
            text-align: justify;
            line-height: 1.8;
        }

        .content p {
            margin-bottom: 15px;
        }

        .kepanitiaan-info {
            margin: 25px 0;
        }

        .kepanitiaan-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .kepanitiaan-info td {
            padding: 8px 5px;
            vertical-align: top;
        }

        .kepanitiaan-info td:first-child {
            width: 200px;
            font-weight: bold;
        }

        .anggota-list {
            margin: 20px 0;
        }

        .anggota-list table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .anggota-list th,
        .anggota-list td {
            border: 1px solid #000;
            padding: 10px;
            text-align: left;
        }

        .anggota-list th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .signature-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 50px;
            margin-bottom: 20px;
        }

        .signature-box {
            text-align: center;
            width: 250px;
        }

        .tanggal-tempat {
            margin-bottom: 40px;
        }

        .qr-code {
            width: 80px;
            height: 80px;
            border: 1px solid #ccc;
            display: inline-block;
            margin: 10px 0;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #666;
        }

        .jabatan {
            font-weight: bold;
            margin-top: 10px;
        }

        .penutup {
            margin: 30px 0;
            text-align: justify;
        }

        .web-only {
            text-align: center;
            margin-bottom: 20px;
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

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .web-only {
                display: none !important;
            }

            .container {
                max-width: none;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Tombol Print Preview -->
        <div class="web-only">
            <button class="btn-print" onclick="window.print()">🖨 Tampilkan Print Preview</button>
        </div>
        <div class="kop-surat">
            <img src="{{ asset('assets/img/pdf/Logo_Politeknik_Negeri_Banyuwangi.png') }}"
                alt="Logo Politeknik Negeri Banyuwangi">
            <div class="kop-surat-text">
                <strong>KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET DAN TEKNOLOGI</strong><br>
                <strong>POLITEKNIK NEGERI BANYUWANGI</strong><br>
                Jalan Raya Jember KM 13 Labanasem Kabat-Banyuwangi, 68461<br>
                Telp/Fax: (0333) 636780; E-mail: poliwangi@poliwangi.ac.id; Laman: poliwangi.ac.id
            </div>
        </div>

        <div class="judul-surat">
            SURAT KEPUTUSAN PEMBENTUKAN KEPANITIAAN
        </div>

        {{-- <div class="nomor-sk">
            Nomor: 001/SK/POLIWANGI/V/2025
        </div> --}}

        <div class="content">
            <p>Yang bertanda tangan di bawah ini, dengan ini membentuk kepanitiaan dan menugaskan kepada nama-nama yang
                tercantum di bawah ini untuk menjadi panitia pelaksana kegiatan sebagai berikut:</p>
        </div>

        <div class="kepanitiaan-info">
            <table>
                <tr>
                    <td>Nama Kegiatan</td>
                    <td>: Seminar Nasional Teknologi dan Inovasi 2025</td>
                </tr>
                <tr>
                    <td>Waktu Pelaksanaan</td>
                    <td>: 15 Juni 2025 - 17 Juni 2025</td>
                </tr>
                <tr>
                    <td>Tempat</td>
                    <td>: Auditorium Politeknik Negeri Banyuwangi</td>
                </tr>
                <tr>
                    <td>Periode Kepanitiaan</td>
                    <td>: 20 Mei 2025 - 30 Juni 2025</td>
                </tr>
            </table>
        </div>

        <div class="content">
            <p><strong>Struktur Kepanitiaan:</strong></p>
        </div>

        <div class="anggota-list">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama</th>
                        <th>NIP/NIDN</th>
                        <th>Jabatan dalam Kepanitiaan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1</td>
                        <td>Dr. Ahmad Fauzi, S.T., M.T.</td>
                        <td>198501152010121001</td>
                        <td>Ketua Pelaksana</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">2</td>
                        <td>Siti Nurhaliza, S.Kom., M.Kom.</td>
                        <td>198903102015042002</td>
                        <td>Sekretaris</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">3</td>
                        <td>Budi Santoso, S.T., M.T.</td>
                        <td>198705201012121003</td>
                        <td>Bendahara</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">4</td>
                        <td>Maya Sari, S.Pd., M.Pd.</td>
                        <td>199002152017042001</td>
                        <td>Koordinator Acara</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">5</td>
                        <td>Eko Prasetyo, S.T., M.T.</td>
                        <td>198608102011121002</td>
                        <td>Koordinator Publikasi</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">6</td>
                        <td>Indah Permata, S.Kom., M.T.</td>
                        <td>199105252018032001</td>
                        <td>Koordinator Konsumsi</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="penutup">
            <p>Demikian surat keputusan pembentukan kepanitiaan ini dibuat untuk dapat dilaksanakan dengan penuh
                tanggung jawab. Kepanitiaan yang telah dibentuk diharapkan dapat menjalankan tugas dan fungsinya dengan
                baik demi kelancaran kegiatan yang dimaksud.</p>
        </div>

        <div class="signature-section">
            <div class="signature-box">
                <div class="tanggal-tempat">Banyuwangi, 12 Mei 2025</div>
                <div>Direktur,</div>
                <div class="qr-code">
                    <svg width="60" height="60" viewBox="0 0 60 60" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect width="60" height="60" fill="white" />
                        <rect x="5" y="5" width="50" height="50" fill="none" stroke="black"
                            stroke-width="1" />
                        <rect x="8" y="8" width="8" height="8" fill="black" />
                        <rect x="20" y="8" width="4" height="4" fill="black" />
                        <rect x="28" y="8" width="4" height="4" fill="black" />
                        <rect x="36" y="8" width="4" height="4" fill="black" />
                        <rect x="44" y="8" width="8" height="8" fill="black" />
                        <rect x="8" y="16" width="4" height="4" fill="black" />
                        <rect x="16" y="16" width="4" height="4" fill="black" />
                        <rect x="24" y="16" width="8" height="8" fill="black" />
                        <rect x="36" y="16" width="4" height="4" fill="black" />
                        <rect x="44" y="16" width="4" height="4" fill="black" />
                        <rect x="8" y="24" width="4" height="4" fill="black" />
                        <rect x="16" y="24" width="8" height="8" fill="black" />
                        <rect x="28" y="24" width="4" height="4" fill="black" />
                        <rect x="36" y="24" width="8" height="8" fill="black" />
                        <rect x="48" y="24" width="4" height="4" fill="black" />
                        <rect x="8" y="32" width="8" height="8" fill="black" />
                        <rect x="20" y="32" width="4" height="4" fill="black" />
                        <rect x="28" y="32" width="8" height="8" fill="black" />
                        <rect x="40" y="32" width="4" height="4" fill="black" />
                        <rect x="48" y="32" width="4" height="4" fill="black" />
                        <rect x="8" y="44" width="8" height="8" fill="black" />
                        <rect x="20" y="44" width="4" height="4" fill="black" />
                        <rect x="28" y="44" width="4" height="4" fill="black" />
                        <rect x="36" y="44" width="8" height="8" fill="black" />
                        <rect x="48" y="44" width="4" height="4" fill="black" />
                    </svg>
                </div>
                <div class="jabatan">Prof. Dr. Ir. Supriadi, M.T.<br>NIP. 196512201990031002</div>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk print halaman
        function printPage() {
            window.print();
        }

        // Auto print ketika halaman dimuat (opsional)
        // window.onload = function() {
        //     setTimeout(printPage, 1000);
        // }
    </script>
</body>

</html>
