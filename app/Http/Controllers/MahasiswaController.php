<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\Mahasiswa_Matakuliah;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\New_;
use Illuminate\Support\Facades\Storage;
use PDF;


class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //fungsi eloquent menampilkan data menggunakan pagination 
        //    $mahasiswa = $mahasiswa = DB::table('mahasiswa')->get(); // Mengambil semua isi tabel
        //paginate 3 mahaiswa
        // $mahasiswa = $mahasiswa = DB::table('mahasiswa')->paginate(3);
        // // Mengambil semua isi tabel 
        // $posts = Mahasiswa::orderBy('nim', 'desc')->paginate(6);      
        // return view('mahasiswa.index', compact('mahasiswa'))-> with('i', (request()
        // ->input('page', 1) - 1) * 5); 
        $mahasiswa = Mahasiswa::with('kelas')->get();
        $mahasiswa = Mahasiswa::orderBy('nim', 'desc')->paginate(3);
        return view('mahasiswa.index', compact('mahasiswa'))->with('i', (request()
            ->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all(); //mendapatkan data dari tabel kelas
        return view('mahasiswa.create', ['kelas' => $kelas]);
    }
    public function store(Request $request)
    {

        // melakukan validasi data
        $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'foto' => 'required', //menambahkan kolom foto
            'kelas' => 'required',
            'jurusan' => 'required',
        ]);
        // menambahkan foto
        // if ($request->file('foto')) {
        //     $image_name = $request->file('foto')->store('images', 'public');
        // }
        $mahasiswa = new Mahasiswa();
        $mahasiswa->nim = $request->get('nim');
        $mahasiswa->nama = $request->get('nama');
        $mahasiswa->foto = $request->file('foto')->store('images', 'public');
        $mahasiswa->kelas_id = $request->get('kelas');
        $mahasiswa->jurusan = $request->get('jurusan');


        $kelas = new Kelas;
        $kelas->id = $request->get('kelas');
        //fungsi eloquent untuk menambah data
        $mahasiswa->kelas()->associate($kelas);
        $mahasiswa->save();

        // Mahasiswa::create($request->all());
        //memanggil

        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($nim)
    {
        //menampilkan detail data dengan menemukan/berdasarkan Nim Mahasiswa
        // $Mahasiswa = Mahasiswa::find($nim);
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        return view('mahasiswa.detail', ['Mahasiswa' => $Mahasiswa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nim)
    {
        //menampilkan detail data dengan menemukan berdasarkan Nim Mahasiswa untuk diedit
        //         $Mahasiswa = DB::table('mahasiswa')->where('nim', $nim)->first();;
        //  return view('mahasiswa.edit', compact('Mahasiswa'));
        $Mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        $kelas = Kelas::all();
        return view('mahasiswa.edit', compact('Mahasiswa', 'kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nim)
    {
        //melakukan validasi data
        $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'kelas' => 'required', //menambahkan kolom foto
            'foto' => 'required',
            'jurusan' => 'required',
            //menambah 3 kolom pada controller
            //  'Email' => ['required', 'Email:dns'],
            //  'Alamat' => 'required',
            //  'tanggal_lahir' => 'required',
        ]);

        $mahasiswa = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        $mahasiswa->nim = $request->get('nim');
        $mahasiswa->nama = $request->get('nama');
        //menambah foto
        if ($mahasiswa->foto && file_exists(storage_path('app/public' . $mahasiswa->foto))) {
            Storage::delete('public/' . $mahasiswa->foto);
        }
        $image_name = $request->file('foto')->store('images', 'public');
        $mahasiswa->foto = $image_name;

        $mahasiswa->kelas_id = $request->get('kelas');
        $mahasiswa->jurusan = $request->get('jurusan');
        $mahasiswa->save();
        // $kelas=new Kelas;
        // $kelas->id=$request->get('kelas');
        // $mahasiswa->kelas()->associate($kelas);
        //fungsi eloquent untuk mengupdate data inputan kita
        // Mahasiswa::find($nim)->update($request->all());
        //jika data berhasil diupdate, akan kembali ke halaman utama
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($nim)
    {
        //fungsi eloquent untuk menghapus data
        Mahasiswa::where('nim', $nim)->delete();
        return redirect()->route('mahasiswa.index')
            ->with('success', 'Mahasiswa Berhasil Dihapus');
    }
    //menambakan fungsi search
    public function search(Request $request)
    {
        $keyword = $request->search;
        $mahasiswa = Mahasiswa::where('nama', 'like', "%" . $keyword . "%")->paginate(3);
        return view('mahasiswa.index', compact('mahasiswa'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
    //menambahkan fungsi nilai
    public function nilai($nim)
    {
        $list = Mahasiswa_Matakuliah::with("matakuliah")->where("mahasiswa_id", $nim)->get();
        $list->mahasiswa = Mahasiswa::with('kelas')->where("nim", $nim)->first();
        return view('mahasiswa.nilai', compact('list'));
    }
    //menambahkan fungsi cetak_pdf (Tugas 2 prak 10)
    public function cetak_pdf($nim)
    {
        // $mahasiswa = Mahasiswa::all();
        $list = Mahasiswa_Matakuliah::with("matakuliah")->where("mahasiswa_id", $nim)->get();
        $list->mahasiswa = Mahasiswa::with('kelas')->where("nim", $nim)->first();
        $pdf = PDF::loadview('mahasiswa.nilai_pdf', compact('list'));
        return $pdf->stream();
    }
}
