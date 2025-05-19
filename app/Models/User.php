<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Klien;

class User extends Authenticatable
{
    use CrudTrait;
    use HasRoles;
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi one-to-one ke tabel klien melalui email
     * (Jika menggunakan email sebagai foreign key)
     */
    public function klien()
    {
        return $this->hasOne(Klien::class, 'email', 'email');
    }

    /**
     * Relasi alternatif jika menggunakan user_id (lebih disarankan)
     * Uncomment jika ingin menggunakan ini sebagai gantinya
     */
    // public function klien()
    // {
    //     return $this->hasOne(Klien::class);
    // }

    /**
     * Cek apakah user adalah klien (optimized)
     */
    public function isKlien(): bool
    {
        return $this->hasRole('klien');
    }

    /**
     * Boot method untuk handle event model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-create klien data ketika user dengan role klien dibuat
        static::created(function ($user) {
            if ($user->hasRole('klien')) {
                Klien::create([
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'nama_klien' => $user->name,
                    'status' => 1
                ]);
            }
        });

        // Update email klien jika email user diubah
        static::updated(function ($user) {
            if ($user->isKlien() && $user->isDirty('email')) {
                $user->klien()->update(['email' => $user->email]);
            }
        });
    }
}
