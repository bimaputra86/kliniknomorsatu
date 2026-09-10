<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

#[Fillable(['id_pasien', 'nik', 'nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'golongan_darah', 'pekerjaan', 'alamat_lengkap', 'nomor_telepon', 'riwayat_alergi', 'jenis_pasien', 'no_bpjs', 'username', 'password'])]
#[Hidden(['password'])]
class Pasien extends Authenticatable
{
    use HasFactory;

    protected $table = 'tbl_pasien';

    protected $primaryKey = 'id_pasien';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
