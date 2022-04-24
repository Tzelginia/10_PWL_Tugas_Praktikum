@extends('mahasiswa.layout')
@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left mt-2">
            <h2 href=>JURUSAN TEKNOLOGI INFORMASI-POLITEKNIK NEGERI MALANG</h2>
        </div>
        <div class="float-right">
            <a class="btn btn-success" href="{{ route('mahasiswa.create') }}"> Input Mahasiswa</a>
        </div>
        <form class="form" method="get" action="{{ route('search') }}">
            <div class="form-group w-100 mb-3">
                <input type="text" name="search" class="form-control w-75 d-inline" id="search" placeholder="Masukkan Kata kunci yang akan dicari">
                <button type="submit" class="btn btn-primary mb-1">Cari</button>
        </form>
    </div>
</div>
</div>
@if ($message = Session::get('success'))
<div class="alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif
@if ($mahasiswa->count())
<table class="table table-bordered">
    <tr>
        <th>Nim</th>
        <th>Nama</th>
        <th>Foto</th>
        <th>Kelas</th>
        <th>Jurusan</th>
        <th width="300">Action</th>
    </tr>
    @foreach ($mahasiswa as $mhs)
    <tr>
        <td>{{ $mhs ->nim }}</td>
        <td>{{ $mhs ->nama }}</td>
        <a class="btn btn-warning mt3" href="{{ route('mahasiswa.cetak_pdf',$list->mahasiswa->nim) }}">Cetak PDF</a>
        <td>{{ $mhs ->kelas->nama_kelas }}</td>
        <td>{{ $mhs ->jurusan }}</td>
        <td>
            <form action="{{ route('mahasiswa.destroy',['mahasiswa'=>$mhs->nim]) }}" method="POST">
                <a class="btn btn-info" href="{{ route('mahasiswa.show',$mhs->nim) }}">Show</a>
                <a class="btn btn-primary" href="{{ route('mahasiswa.edit',$mhs->nim) }}">Edit</a>
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
                <a class="btn btn-warning" href="{{ route('nilai',$mhs->nim) }}">Nilai</a>
            </form>
        </td>
    </tr>
    @endforeach
    @else
    <p class="text-center fs-4">Mahasiswa tidak ditemukan.</p>
    @endif
</table>
{{$mahasiswa->links()}}
@endsection