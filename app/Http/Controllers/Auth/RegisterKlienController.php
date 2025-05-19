<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Klien;
use Backpack\PermissionManager\app\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterKlienController extends Controller
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'nama_klien' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Hapus validasi untuk no_telp dan nik (opsional)
        ]);
    }

    protected function create(array $data)
    {
        // 1. Buat User
        $user = User::create([
            'name' => $data['nama_klien'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // 2. Assign Role Klien
        $role = Role::firstOrCreate(['name' => 'klien']);
        $user->assignRole($role);

        // 3. Buat Data Klien (HANYA email, nama_klien, dan status)
        Klien::create([
            'user_id' => $user->id, // Tambahkan ini jika pakai user_id
            'nama_klien' => $data['nama_klien'],
            'email' => $data['email'],
            'status' => 1, // Default aktif
            // Sisanya biarkan NULL
        ]);

        return $user;
    }

    public function showRegistrationForm()
    {
        return view('auth.register_klien'); // Pastikan form hanya minta nama, email, password
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        auth()->login($user);
        return redirect()->route('klien.dashboard');
    }
}
