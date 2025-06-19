<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Laporan Hasil Belajar - {{ $student->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            margin: 30px;
        }

        .header-table,
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .header-table td {
            padding: 2px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid black;
            padding: 6px;
            vertical-align: top;
        }

        .main-table th {
            background-color: #e9e9e9;
            font-weight: bold;
            text-align: center;
        }

        .main-table .no-col {
            width: 5%;
            text-align: center;
        }

        .main-table .score-col {
            width: 10%;
            text-align: center;
        }

        .main-table .description-col {
            text-align: justify;
        }

        h2,
        h3 {
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h2 {
            font-size: 14pt;
            margin-bottom: 2px;
        }

        h3 {
            font-size: 12pt;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        /* CSS untuk layout bagian bawah */
        .footer-container {
            margin-top: 20px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            vertical-align: top;
        }

        .attendance-decision-table {
            width: 100%;
            border: 1px solid black;
        }

        .attendance-decision-table td {
            padding: 5px;
        }

        .signature-table {
            width: 100%;
            margin-top: 20px;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>LAPORAN HASIL BELAJAR</h2>
    <h3>(RAPOR)</h3>

    <table class="header-table">
        <tr>
            <td style="width: 20%;">Nama Peserta Didik</td>
            <td style="width: 2%;">:</td>
            <td style="width: 48%;"><b>{{ $student->name }}</b></td>
            <td style="width: 20%;">Kelas</td>
            <td style="width: 2%;">:</td>
            <td style="width: 8%;">{{ $settings->class_level }}</td>
        </tr>
        <tr>
            <td>NISN</td>
            <td>:</td>
            <td>{{ $student->nisn ?? '-' }}</td>
            <td>Fase</td>
            <td>:</td>
            <td>{{ $settings->phase }}</td>
        </tr>
        <tr>
            <td>Sekolah</td>
            <td>:</td>
            <td>{{ $settings->school_name }}</td>
            <td>Semester</td>
            <td>:</td>
            <td>{{ $settings->semester === 1 ? '1 (Ganjil)' : '2 (Genap)' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $settings->address }}</td>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td>{{ $settings->academic_year }}</td>
        </tr>
    </table>
    <hr>

    <!-- Tabel 1: Nilai Akademik -->
    <p class="section-title">A. Capaian Akademik</p>
    <table class="main-table">
        <thead>
            <tr>
                <th class="no-col">No</th>
                <th>Muatan Pelajaran</th>
                <th class="score-col">Nilai Akhir</th>
                <th class="description-col">Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject)
                @php
                    $outcome = $learningOutcomes->firstWhere('subject_id', $subject->id);
                @endphp
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ $subject->name }}</td>
                    <td class="score-col">{{ $outcome ? number_format($outcome->report_score, 2) : '-' }}</td>
                    <td class="description-col">
                        {{-- Di sini kita perlu logika untuk generate deskripsi --}}
                        {{ $outcome ? $outcome->description : 'Belum ada capaian kompetensi yang terekam.' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tabel 2: Ekstrakurikuler -->
    @if (count($student->extracurriculars) > 0)
        <p class="section-title">B. Ekstrakurikuler</p>
        <table class="main-table">
            <thead>
                <tr>
                    <th class="no-col">No</th>
                    <th>Kegiatan Ekstrakurikuler</th>
                    <th class="score-col">Predikat</th>
                    <th class="description-col">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($student->extracurriculars as $eskul)
                    <tr>
                        <td class="no-col">{{ $loop->iteration }}</td>
                        <td>{{ $eskul->extracurricular->name }}</td>
                        <td class="score-col">{{ $eskul->predicate }}</td>
                        <td class="description-col">{{ $eskul->description }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer-container">
        <table class="footer-table">
            <tr>
                <!-- Kolom Kiri: Ketidakhadiran -->
                <td style="width: 50%; padding-right: 10px;">
                    <p class="section-title">C. Ketidakhadiran</p>
                    <table class="attendance-decision-table">
                        <tr>
                            <td style="width: 60%;">Sakit</td>
                            <td>: {{ $student->attendance_sick }} hari</td>
                        </tr>
                        <tr>
                            <td>Izin</td>
                            <td>: {{ $student->attendance_permission }} hari</td>
                        </tr>
                        <tr>
                            <td>Tanpa Keterangan</td>
                            <td>: {{ $student->attendance_alpha }} hari</td>
                        </tr>
                    </table>
                </td>
                <!-- Kolom Kanan: Keputusan -->
                <td style="width: 50%; padding-left: 10px;">
                    <p class="section-title" style="color:white;"> </p> <!-- Spacer -->
                    <table>
                        <tr>
                            <td style="padding: 10px;">
                                <b>Keputusan :</b><br>
                                Berdasarkan pencapaian seluruh kompetensi, peserta didik dinyatakan
                                <br><br>
                                Naik/Tinggal Kelas : ...................................<br>
                                Fase : .........................................................
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>


    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td>
                <p>Orang Tua/Wali,</p>
                <br><br><br><br>
                <p>.........................................</p>
            </td>
            <td> </td>
            <td>
                <p>{{ $settings->district }},
                    {{ \Carbon\Carbon::parse($settings->report_date)->isoFormat('D MMMM YYYY') }}</p>
                <p>Wali Kelas,</p>
                <br><br><br><br>
                <p><b><u>{{ $teacherName }}</u></b></p>
                <p>NIP. {{ $teacherNip }}</p>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding-top: 30px;">
                <p>Mengetahui,</p>
                <p>Kepala Sekolah,</p>
                <br><br><br><br>
                <p><b><u>{{ $settings->headmaster_name }}</u></b></p>
                <p>NIP. {{ $settings->headmaster_nip }}</p>
            </td>
        </tr>
    </table>

</body>

</html>
