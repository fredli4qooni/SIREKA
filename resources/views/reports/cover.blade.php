<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Identitas Rapor</title>
    <style>
        /* Pengaturan Halaman Global */
        @page {
            /* Margin standar untuk dokumen formal */
            margin: 2.5cm; 
        }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            line-height: 1.5;
        }
        .page-break { 
            page-break-after: always; 
        }
        
        /* Kontainer Konten untuk Setiap Halaman */
        .page-content {
            width: 100%;
        }

        /* Styling Judul Halaman */
        h3 { 
            margin: 0 0 30px 0; 
            padding-bottom: 5px;
            border-bottom: 1px solid black;
            text-align: center; 
            font-size: 14pt;
            font-weight: bold;
        }

        /* Tabel Konten Utama (digunakan di kedua halaman) */
        .content-table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .content-table td { 
            padding: 4px 0; 
            vertical-align: top; 
        }
        .content-table .label { width: 35%; }
        .content-table .separator { width: 2%; text-align: center; }
        .content-table .data { width: 63%; }

        /* Styling khusus untuk sub-judul di halaman 2 */
        .sub-heading {
            font-weight: bold;
            margin-top: 15px;
            margin-bottom: 5px;
        }
        .sub-heading-item {
            padding-left: 15px;
        }

        /* Styling Bagian Bawah Halaman 2 (Foto & Tanda Tangan) */
        .footer-section {
            width: 100%;
            margin-top: 50px; /* Jarak dari konten di atasnya */
        }
        .footer-section td {
            width: 50%;
            vertical-align: top;
        }
        .photo-cell {
            text-align: center;
        }
        .signature-cell {
            text-align: left;
            padding-left: 1cm;
        }
        .photo-box {
            width: 3cm;
            height: 4cm;
            border: 1px solid black;
            margin: 0 auto; /* Tengah di dalam kolomnya */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10pt;
        }
        .signature-block {
            /* Memberi ruang kosong untuk tanda tangan basah */
            height: 80px; 
        }

    </style>
</head>
<body>

    <!-- 
    ======================================
    HALAMAN 1: IDENTITAS SEKOLAH
    ======================================
    -->
    <div class="page-content">
        <h3>IDENTITAS SEKOLAH</h3>
        
        <table class="content-table">
            <tr><td class="label">Nama Sekolah</td><td class="separator">:</td><td class="data">{{ $settings->school_name }}</td></tr>
            <tr><td class="label">NPSN</td><td class="separator">:</td><td class="data">{{ $settings->npsn }}</td></tr>
            <tr><td class="label">Alamat Sekolah</td><td class="separator">:</td><td class="data">{{ $settings->address }}</td></tr>
            <tr><td class="label">Kode Pos</td><td class="separator">:</td><td class="data">{{ $settings->postal_code }}</td></tr>
            <tr><td class="label">Desa / Kelurahan</td><td class="separator">:</td><td class="data">{{ $settings->village }}</td></tr>
            <tr><td class="label">Kecamatan</td><td class="separator">:</td><td class="data">{{ $settings->sub_district }}</td></tr>
            <tr><td class="label">Kabupaten / Kota</td><td class="separator">:</td><td class="data">{{ $settings->district }}</td></tr>
            <tr><td class="label">Provinsi</td><td class="separator">:</td><td class="data">{{ $settings->province }}</td></tr>
            <tr><td class="label">E-mail</td><td class="separator">:</td><td class="data">{{ $settings->email }}</td></tr>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- 
    ======================================
    HALAMAN 2: IDENTITAS PESERTA DIDIK
    ======================================
    -->
    <div class="page-content">
        <h3>IDENTITAS PESERTA DIDIK</h3>
        
        <table class="content-table">
            <tr><td class="label">Nama Peserta Didik</td><td class="separator">:</td><td class="data">{{ $student->name }}</td></tr>
            <tr><td class="label">NISN / NIS</td><td class="separator">:</td><td class="data">{{ $student->nisn ?? '-' }} / {{ $student->nis }}</td></tr>
            <tr><td class="label">Tempat, Tanggal Lahir</td><td class="separator">:</td><td class="data">{{ $student->birth_place }}, {{ \Carbon\Carbon::parse($student->birth_date)->isoFormat('D MMMM YYYY') }}</td></tr>
            <tr><td class="label">Jenis Kelamin</td><td class="separator">:</td><td class="data">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
            <tr><td class="label">Agama</td><td class="separator">:</td><td class="data">{{ $student->religion }}</td></tr>
            <tr><td class="label">Pendidikan sebelumnya</td><td class="separator">:</td><td class="data">{{ $student->previous_education }}</td></tr>
            <tr><td class="label">Alamat Peserta Didik</td><td class="separator">:</td><td class="data">{{ $student->address }}</td></tr>
            <tr><td colspan="3"><div class="sub-heading">Nama Orang Tua</div></td></tr>
            <tr><td class="label sub-heading-item">a. Ayah</td><td class="separator">:</td><td class="data">{{ $student->father_name }}</td></tr>
            <tr><td class="label sub-heading-item">b. Ibu</td><td class="separator">:</td><td class="data">{{ $student->mother_name }}</td></tr>
            <tr><td colspan="3"><div class="sub-heading">Pekerjaan Orang Tua</div></td></tr>
            <tr><td class="label sub-heading-item">a. Ayah</td><td class="separator">:</td><td class="data">{{ $student->father_job }}</td></tr>
            <tr><td class="label sub-heading-item">b. Ibu</td><td class="separator">:</td><td class="data">{{ $student->mother_job }}</td></tr>
            <tr><td colspan="3"><div class="sub-heading">Alamat Orang Tua</div></td></tr>
            <tr><td class="label sub-heading-item">Jalan</td><td class="separator">:</td><td class="data">{{ $student->parent_address_street }}</td></tr>
            <tr><td class="label sub-heading-item">Kelurahan/Desa</td><td class="separator">:</td><td class="data">{{ $student->parent_address_village }}</td></tr>
            <tr><td class="label sub-heading-item">Kecamatan</td><td class="separator">:</td><td class="data">{{ $student->parent_address_sub_district }}</td></tr>
            <tr><td colspan="3"><div class="sub-heading">Wali Peserta Didik</div></td></tr>
            <tr><td class="label sub-heading-item">Nama</td><td class="separator">:</td><td class="data">{{ $student->guardian_name ?? '-' }}</td></tr>
            <tr><td class="label sub-heading-item">Pekerjaan</td><td class="separator">:</td><td class="data">{{ $student->guardian_job ?? '-' }}</td></tr>
            <tr><td class="label sub-heading-item">Alamat</td><td class="separator">:</td><td class="data">{{ $student->guardian_address ?? '-' }}</td></tr>
        </table>
        
        <!-- Bagian Bawah: Foto & Tanda Tangan -->
        <table class="footer-section">
            <tr>
                <td class="photo-cell">
                    <div class="photo-box">
                        <span>Pas Foto<br>3 x 4</span>
                    </div>
                </td>
                <td class="signature-cell">
                    {{ $settings->report_place }}, {{ \Carbon\Carbon::parse($settings->report_date)->isoFormat('D MMMM YYYY') }}<br>
                    Kepala Sekolah,
                    <div class="signature-block"></div>
                    <b><u>{{ $settings->headmaster_name }}</u></b><br>
                    NIP. {{ $settings->headmaster_nip }}
                </td>
            </tr>
        </table>
    </div>

</body>
</html>