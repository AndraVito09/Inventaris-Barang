<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Throwable;

class Auth_log extends Model
{
    protected $table = 'auth_log';
    protected $primaryKey = 'id_auth_log';

    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_admin',
        'username_input',
        'aksi',
        'ip_address',
        'user_agent',
        'keterangan'
    ];

    public static function catat(
        string $aksi,
        string $username,
        ?int $idAdmin = null,
        ?string $keterangan = null
    ): void {
        try {
            static::create([
                'id_admin'       => $idAdmin,
                'username_input' => mb_substr($username, 0, 50),
                'aksi'           => $aksi,
                'ip_address'     => request()->ip(),
                'user_agent'     => mb_substr((string) request()->userAgent(), 0, 255) ?: null,
                'keterangan'     => $keterangan !== null ? mb_substr($keterangan, 0, 255) : null,
            ]);
        } catch (Throwable $e) {
            report($e);
        }
    }

    public static function catat_login_berhasil(Admin $admin): void
    {
        static::catat('login_berhasil', $admin->username, $admin->id_admin, 'Login berhasil');
    }

    public static function catat_login_gagal(string $username): void
    {
        $idAdmin = Admin::where('username', $username)->value('id_admin');
 
        static::catat(
            'login_gagal',
            $username,
            $idAdmin,
            $idAdmin ? 'Password salah' : 'Username tidak terdaftar'
        );
    }
 
    public static function catat_logout(Admin $admin): void
    {
        static::catat('logout', $admin->username, $admin->id_admin, 'Logout');
    }
}
