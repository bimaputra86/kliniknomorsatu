<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_pembayaran', 'id_pemeriksaan', 'tanggal_pembayaran', 'biaya_layanan_medis', 'biaya_obat', 'total_tagihan', 'nominal_bayar', 'kembalian', 'status_pembayaran'])]
class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'tbl_pembayaran';

    protected $primaryKey = 'id_pembayaran';

    public $incrementing = false;

    protected $keyType = 'string';

    public function pemeriksaan()
    {
        return $this->belongsTo(Pemeriksaan::class, 'id_pemeriksaan', 'id_pemeriksaan');
    }
}
