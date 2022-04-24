<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa_Matakuliah extends Model
{
    use HasFactory;
    protected $table='mahasiswa_matakuliah'; // Eloquent akan membuat model mahasiswa menyimpan record di tabel mahasiswa
    protected $primaryKey = 'id'; // Memanggil isi DB Dengan primarykey
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'id',
    'mahasiswa_id',
    'matakuliah_id',
    'nilai',
    ];
public function mahasiswa(){
    return $this->belongsTo(Mahasiswa::class,'mahasiswa_id','nim');
}
public function matakuliah(){
return $this->belongsTo(Matakuliah::class,'matakuliah_id');
}
}
