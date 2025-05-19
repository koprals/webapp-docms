<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class JenisDokumen extends Model
{
    use CrudTrait;
    protected $table = 'jenis_dokumen';
    protected $primaryKey = 'id_jenis';
    protected $fillable = [
        'nama_jenis',
        'deskripsi',
        'status'
    ];

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'id_jenis');
    }
}
