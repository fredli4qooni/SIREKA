<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Buku Induk Siswa</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11pt;
            margin: 30px;
        }

        .header-table,
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .header-table td {
            padding: 3px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid black;
            padding: 8px;
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
            width: 15%;
            text-align: center;
        }

        .main-table .sub-item {
            padding-left: 30px;
        }

        .main-table .no-border {
            border: none;
        }

        h2 {
            text-align: center;
            font-size: 14pt;
            margin-bottom: 20px;
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <h2>BUKU INDUK PESERTA DIDIK</h2>

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
            <td>{{ $student->address }}</td>
            <td>Tahun Pelajaran</td>
            <td>:</td>
            <td>{{ $settings->academic_year }}</td>
        </tr>
    </table>

    <!-- Tabel 1: Nilai Pelajaran -->
    <table class="main-table">
        <thead>
            <tr>
                <th class="no-col">No</th>
                <th>Muatan Pelajaran</th>
                <th class="score-col">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subjects as $subject)
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ $subject->name }}</td>
                    <td class="score-col">
                        {{-- CARA YANG BENAR MENCARI NILAI --}}
                        @php
                            // Cari di dalam collection $reportScores, record yang subject_id-nya cocok
                            $scoreRecord = $reportScores->firstWhere('subject_id', $subject->id);
                        @endphp

                        {{-- Tampilkan report_score jika record ditemukan, jika tidak, tampilkan strip --}}
                        {{ $scoreRecord ? number_format($scoreRecord->report_score, 2) : '-' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Tabel 2: Ekstrakurikuler -->
    <table class="main-table">
        <thead>
            <tr>
                <th class="no-col">No</th>
                <th>EKSTRAKURIKULER</th>
                <th class="score-col">PREDIKAT</th>
            </tr>
        </thead>
        <tbody>
            @forelse($student->extracurriculars as $eskul)
                <tr>
                    <td class="no-col">{{ $loop->iteration }}</td>
                    <td>{{ $eskul->extracurricular->name }}</td>
                    <td class="score-col">{{ $eskul->predicate }}</td>
                </tr>
            @empty
                {{-- Tampilkan baris kosong jika tidak ada ekskul --}}
                @for ($i = 1; $i <= 3; $i++)
                    <tr>
                        <td class="no-col">{{ $i }}</td>
                        <td> </td>
                        <td class="score-col">-</td>
                    </tr>
                @endfor
            @endforelse
        </tbody>
    </table>


    <!-- Tabel 3: Ketidakhadiran -->
    <table class="main-table" style="width: 50%;">
        <thead>
            <tr>
                <th colspan="3">KETIDAKHADIRAN</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width: 5%; text-align:center;">1</td>
                <td style="width: 75%;">Sakit</td>
                <td style="width: 20%; text-align:center;">{{ $student->attendance_sick }} hari</td>
            </tr>
            <tr>
                <td style="width: 5%; text-align:center;">2</td>
                <td style="width: 75%;">Izin</td>
                <td style="width: 20%; text-align:center;">{{ $student->attendance_permission }} hari</td>
            </tr>
            <tr>
                <td style="width: 5%; text-align:center;">3</td>
                <td style="width: 75%;">Tanpa Keterangan</td>
                <td style="width: 20%; text-align:center;">{{ $student->attendance_alpha }} hari</td>
            </tr>
        </tbody>
    </table>

</body>

</html>
