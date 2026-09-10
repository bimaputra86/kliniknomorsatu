<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['tanggal_antrean', 'nomor_antrean', 'id_pasien', 'status_antrean'])]
class Antrean extends Model
{
    use HasFactory;

    protected $table = 'tbl_antrean';

    protected $primaryKey = 'id_antrean';

    public $incrementing = true;

    protected $keyType = 'int';

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }
}
