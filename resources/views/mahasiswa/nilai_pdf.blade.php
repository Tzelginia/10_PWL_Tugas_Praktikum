@extends('mahasiswa.layout')
<!DOCTYPE html>
<html>

<head>
    <title>LAPORAN CETAK KARTU HASIL STUDI</title>
</head>

<body>
    <style type="text/css">
        table tr td,
        table tr th {
            font-size: 9pt;
        }
    </style>
    <center>
        <h2>Laporan KHS</h2>
    </center>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Mata Kuliah</th>
                <th scope="col">SKS</th>
                <th scope="col">Semester</th>
                <th scope="col">Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $ls)
            <tr scope="row">
                <td>
                    {{$ls->matakuliah->nama_matkul}}
                </td>
                <td>
                    {{$ls->matakuliah->sks}}
                </td>
                <td>
                    {{$ls->matakuliah->semester}}
                </td>
                <td>
                    {{$ls ->nilai}}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>