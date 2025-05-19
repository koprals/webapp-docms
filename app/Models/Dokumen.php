<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Dokumen extends Model
{
    use CrudTrait; // Tambahkan ini

    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';
    public $timestamps = false;

    protected $fillable = [
        'id_permohonan',
        'id_jenis',
        'source',
        'status'
    ];

    // Relasi ke JenisDokumen
    public function jenisDokumen()
    {
        return $this->belongsTo(JenisDokumen::class, 'id_jenis');
    }

    // Relasi ke Permohonan
    public function permohonan()
    {
        return $this->belongsTo(Permohonan::class, 'id_permohonan');
    }
}
