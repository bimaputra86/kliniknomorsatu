<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_obat', 'nama_obat', 'jenis_obat', 'harga_satuan', 'stok', 'satuan'])]
class Obat extends Model
{
    use HasFactory;

    protected $table = 'tbl_obat';

    protected $primaryKey = 'id_obat';

    public $incrementing = false;

    protected $keyType = 'string';
}
