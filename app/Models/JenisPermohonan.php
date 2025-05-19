<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class JenisPermohonan extends Model
{
    use CrudTrait;
    protected $table = 'jenis_permohonan';
    protected $primaryKey = 'id_jenis';
    protected $fillable = [
        'nama_jenis',
        'deskripsi'
    ];

    public function permohonan()
    {
        return $this->hasMany(Permohonan::class, 'id_jenis_permohonan');
    }
}
