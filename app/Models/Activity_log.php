<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Activity_log extends Model
{
    protected $table = 'activity_log';
    protected $primaryKey = 'id_activity_log';

    public $timestamps = true;
    const UPDATED_AT = null;

    protected $fillable = [
        'id_admin',
        'aktor',
        'aksi',
        'nama_tabel',
        'id_data',
        'deskripsi',
        'ip_address'
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }

    public static function catat_aktivitas_admin(string $aksi, string $tabel, ?int $idData, string $deskripsi, string $aktor = 'admin'): void
    {
        static::create([
            'id_admin'   => $aktor === 'admin' ? Auth::id() : null,
            'aktor'      => $aktor,
            'aksi'       => $aksi,
            'nama_tabel' => $tabel,
            'id_data'    => $idData,
            'deskripsi'  => mb_substr($deskripsi, 0, 255),
            'ip_address' => request()->ip(),
        ]);
    }
    
    public static function catat_aktivitas_peminjam(string $aksi, string $tabel, ?int $idData, string $deskripsi,): void
    {
        static::create([
            'id_admin'   => null,
            'aktor'      => 'peminjam',
            'aksi'       => $aksi,
            'nama_tabel' => $tabel,
            'id_data'    => $idData,
            'deskripsi'  => mb_substr($deskripsi, 0, 255),
            'ip_address' => request()->ip(),
        ]);
    }
}
