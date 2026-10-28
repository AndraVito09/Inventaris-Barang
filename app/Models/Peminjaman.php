<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $primaryKey = 'id_peminjaman';
    public $timestamps = true;

    protected $fillable = ['id_barang', 'id_peminjam', 'tgl_pinjam', 'tgl_jatuh_tempo', 'tgl_kembali', 'status_peminjaman'];

    // Agar kolom tanggal bisa dipakai ->format() di view
    protected $casts = [
        'tgl_pinjam'      => 'datetime',
        'tgl_jatuh_tempo' => 'datetime',
        'tgl_kembali'     => 'datetime',
    ];

    // Relasi ke Barang dan Peminjam
    public function barang() { return $this->belongsTo(Barang::class, 'id_barang', 'id_barang'); }
    public function peminjam() { return $this->belongsTo(Peminjam::class, 'id_peminjam', 'id_peminjam'); }

    // Kode transaksi dihitung, tidak disimpan di database.
    public function getKodeTransaksiAttribute(): string
    {
        $tanggal = $this->tgl_pinjam->format('Ymd');
        $nomor   = str_pad($this->id_peminjaman, 5, '0', STR_PAD_LEFT);

        return "TRX-{$tanggal}-{$nomor}-" . self::tandaKode((int) $this->id_peminjaman, $tanggal);
    }

    // Cari peminjaman dari kode transaksi (dipakai di struk dan nanti di pengembalian)
    public static function cariDariKode(string $kode): ?self
    {
        if (! preg_match('/^TRX-(\d{8})-(\d{5,})-([A-Z0-9]{12})$/', strtoupper(trim($kode)), $cocok)) {
            return null;
        }

        [, $tanggal, $nomor, $tanda] = $cocok;
        $nomor = (int) $nomor;

        // Tanda pengaman harus cocok, kalau tidak berarti kode palsu atau salah ketik
        if (! hash_equals(self::tandaKode($nomor, $tanggal), $tanda)) {
            return null;
        }

        $peminjaman = static::find($nomor);

        return ($peminjaman && $peminjaman->tgl_pinjam->format('Ymd') === $tanggal) ? $peminjaman : null;
    }

    // 5 karakter pengaman, dihitung dari nomor, tanggal, dan APP_KEY
    private static function tandaKode(int $nomor, string $tanggal): string
    {
        return strtoupper(substr(hash_hmac('sha256', "{$nomor}|{$tanggal}", config('app.key')), 0, 12));
    }
}