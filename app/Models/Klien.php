<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Klien extends Model
{
    use CrudTrait;
    protected $table = 'klien';
    protected $primaryKey = 'id_klien';

    protected $fillable = [
        'nama_klien',
        'email',
        'no_telp',
        'nik',
        'tgl_lahir',
        'alamat',
        'status',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'status' => 'boolean',
    ];

    /**
     * Relasi balik ke tabel users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }

    /**
     * Scope untuk klien aktif
     */
    public function scopeAktif($query)
    {
        return $query->where('status', 1);
    }
}
