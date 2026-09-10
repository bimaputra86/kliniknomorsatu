<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_pemeriksaan', 'tanggal_pemeriksaan', 'id_pasien', 'id_pengguna', 'tekanan_darah', 'suhu_tubuh', 'berat_badan', 'keluhan_utama', 'diagnosis_penyakit', 'tindakan_medis'])]
class Pemeriksaan extends Model
{
    use HasFactory;

    protected $table = 'tbl_pemeriksaan';

    protected $primaryKey = 'id_pemeriksaan';

    public $incrementing = false;

    protected $keyType = 'string';

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function pengguna()
    {
        return $this->belongsTo(User::class, 'id_pengguna', 'id_pengguna');
    }
}
