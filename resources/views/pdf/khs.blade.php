<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Hasil Studi (KHS)</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: auto;
        }
        .university-name {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            text-align: center;
        }
        .student-info {
            margin-bottom: 15px;
        }
        .student-info table {
            width: 100%;
            max-width: 100%;
            border: none;
        }
        .student-info td {
            padding: 3px;
        }
        .grades-table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .grades-table th, .grades-table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        .grades-table th {
            background-color: #f0f0f0;
        }
        .summary {
            margin-top: 15px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/logo_poltekab.png') }}" alt="Logo Universitas" class="logo">
        <div class="university-name">POLITEKNIK KOTABARU</div>
        <div>Jl. Raya Stagen KM 9,5, RT 14, Stagen, Pulau Laut Utara, Stagen, Kec. Pulau Laut Utara, Kab. Kotabaru, Kalimantan Selatan 72114</div>
        <div>Telp (0518) 6076838</div>
        <div>Website: https://poltekab.ac.id </div>
    </div>

    <div class="title">KARTU HASIL STUDI (KHS)</div>

    <div class="student-info">
        <table>
            <tr>
                <td width="120">Nama</td>
                <td width="10">:</td>
                <td>{{ $studyResult->student->user->name }}</td>
            </tr>
            <tr>
                <td>NIM</td>
                <td>:</td>
                <td>{{ $studyResult->student->student_number }}</td>
            </tr>
            <tr>
                <td>Program Studi</td>
                <td>:</td>
                <td>{{ $studyResult->student->department->name }}</td>
            </tr>
            <tr>
                <td>Semester</td>
                <td>:</td>
                <td>{{ $studyResult->semester }}</td>
            </tr>
            <tr>
                <td>Tahun Akademik</td>
                <td>:</td>
                <td>{{ $studyResult->academicYear->name }}</td>
            </tr>
        </table>
    </div>

    <table class="grades-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Nilai</th>
                <th>Bobot</th>
                <th>N x K</th>
            </tr>
        </thead>
        <tbody>
            @php $total_credits = 0; $total_quality_points = 0; @endphp
            @foreach($studyResult->grades as $index => $grade)
                @php
                    $quality_points = $grade->grade_point * $grade->course->credit;
                    $total_credits += $grade->course->credit;
                    $total_quality_points += $quality_points;
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $grade->course->code }}</td>
                    <td>{{ $grade->course->name }}</td>
                    <td>{{ $grade->course->credit }}</td>
                    <td>{{ $grade->grade }}</td>
                    <td>{{ number_format($grade->grade_point, 2) }}</td>
                    <td>{{ number_format($quality_points, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" align="right"><strong>Total:</strong></td>
                <td><strong>{{ $total_credits }}</strong></td>
                <td colspan="2" align="right"><strong>IP Semester:</strong></td>
                <td><strong>{{ number_format($studyResult->gpa, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
