<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_pemeriksaan', 'id_obat', 'jumlah_obat', 'dosis_aturan_pakai'])]
class DetailResep extends Model
{
    use HasFactory;

    protected $table = 'tbl_detail_resep';

    protected $primaryKey = 'id_resep';

    public $incrementing = true;

    protected $keyType = 'int';

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'id_obat', 'id_obat');
    }
}
