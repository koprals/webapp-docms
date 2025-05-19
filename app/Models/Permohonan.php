<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Permohonan extends Model
{
    use CrudTrait;
    protected $table = 'permohonan';
    protected $primaryKey = 'id_permohonan';
    protected $fillable = [
        'id_klien',
        'id_jenis_permohonan',
        'tgl_input',
        'alamat_pihak_satu',
        'alamat_pihak_dua',
        'no_pbb',
        'no_settifikat',
        'luas_tanah',
        'status'
    ];

    public function klien()
    {
        return $this->belongsTo(Klien::class, 'id_klien');
    }

    public function jenisPermohonan()
    {
        return $this->belongsTo(JenisPermohonan::class, 'id_jenis_permohonan');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_permohonan');
    }
}
