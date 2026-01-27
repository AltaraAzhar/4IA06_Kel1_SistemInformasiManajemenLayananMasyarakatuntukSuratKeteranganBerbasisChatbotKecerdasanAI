<?php

namespace App\Models;

// Note: Using MongoDB\Laravel\Eloquent\Model (from mongodb/laravel-mongodb package)
// If you need Jenssegers\Mongodb\Eloquent\Model, ensure jenssegers/mongodb package is installed
use MongoDB\Laravel\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pengajuan_surat';
    
    protected $fillable = [
        'user_id',
        'jenis_layanan',
        'nama',
        'nik',
        'alamat',
        'no_hp',
        'dokumen',
        'status',
        'etiket',
        'nomor_pengajuan',
        'catatan_admin',
        'keterangan',
        'nomor_surat',
        'file_surat',
        'processed_at',
        'admin_id',
        'preview_token',
        'preview_expired_at',
        'created_at',
        'updated_at',
    ];
    
    public $timestamps = true; // Enable timestamps

    protected $casts = [
        'dokumen' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'processed_at' => 'datetime',
        'preview_expired_at' => 'datetime',
    ];
    
    /**
     * Generate preview token
     */
    public static function generatePreviewToken()
    {
        return \Illuminate\Support\Str::uuid()->toString();
    }
    
    /**
     * Check if preview token is valid
     */
    public function isPreviewTokenValid()
    {
        if (!$this->preview_token) {
            return false;
        }
        
        if (!$this->preview_expired_at) {
            return false;
        }
        
        return now()->isBefore($this->preview_expired_at);
    }
    
    /**
     * Relationship: Pengajuan has many history
     */
    public function history()
    {
        return $this->hasMany(PengajuanHistory::class, 'pengajuan_id');
    }

    /**
     * Relationship: Pengajuan belongs to User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Status constants
     */
    const STATUS_DIAJUKAN = 'diajukan';
    const STATUS_MENUNGGU = 'menunggu';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_REVISI = 'revisi';
    const STATUS_SELESAI = 'selesai'; // Selesai

    /**
     * Get supported jenis layanan
     */
    public static function getSupportedLayanan()
    {
        return [
            'Surat Keterangan Usaha',
            'Surat Keterangan Tidak Mampu',
            'Surat Keterangan Kelahiran',
            'Surat Keterangan Kematian',
            'Surat Pengantar PBB',
        ];
    }

    /**
     * Generate nomor pengajuan
     */
    public static function generateNomorPengajuan($jenisLayanan)
    {
        $prefix = strtoupper(substr(str_replace(' ', '', $jenisLayanan), 0, 3));
        $timestamp = now()->timestamp;
        return $prefix . '-' . $timestamp;
    }

    /**
     * Generate nomor antrian (e-tiket)
     */
    public static function generateNomorAntrian()
    {
        $year = date('Y');
        $count = self::whereYear('created_at', $year)
            ->whereNotNull('nomor_antrian')
            ->count() + 1;
        return 'ETK-' . $year . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $status = $this->status ?? self::STATUS_DIAJUKAN;
        
        return match($status) {
            'diajukan', 'menunggu' => 'bg-orange-100 text-orange-800',
            'diproses' => 'bg-blue-100 text-blue-800',
            'direvisi', 'revisi' => 'bg-red-100 text-red-800',
            'selesai' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        $status = $this->status ?? self::STATUS_DIAJUKAN;
        
        return match($status) {
            'diajukan', 'menunggu' => 'Diajukan',
            'diproses' => 'Diproses',
            'direvisi', 'revisi' => 'Direvisi',
            'selesai' => 'Selesai',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Generate etiket
     * Format: SKTM:XXXXXXXXXXXX-XXX (untuk SKTM)
     * Format: KPM-YYYY-XXXX (untuk layanan lain)
     */
    public static function generateEtiket($jenisLayanan = null, $nik = null)
    {
        // Jika SKTM, gunakan format khusus: SKTM:XXXXXXXXXXXX-XXX
        if ($jenisLayanan === 'Surat Keterangan Tidak Mampu' && $nik) {
            // Ambil 12 digit pertama dari NIK
            $nikPrefix = substr($nik, 0, 12);
            
            // Hitung nomor antrian untuk SKTM tahun ini
            $year = date('Y');
            $count = self::where('jenis_layanan', 'Surat Keterangan Tidak Mampu')
                ->where('etiket', 'like', 'SKTM:%')
                ->whereYear('created_at', $year)
                ->count() + 1;
            
            // Format: SKTM:XXXXXXXXXXXX-XXX
            return 'SKTM:' . $nikPrefix . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
        }
        
        // Format default untuk layanan lain
        $year = date('Y');
        $count = self::where('etiket', 'like', 'KPM-' . $year . '-%')
            ->count() + 1;
        return 'KPM-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Relationship: Pengajuan has many notifications
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'pengajuan_id');
    }

    /**
     * Get list of layanan that require e-tiket (static method)
     * Used for checking if a layanan requires e-tiket
     * Called as: PengajuanSurat::layananEtiket()
     */
    public static function layananEtiket(): array
    {
        return [
            'Surat Keterangan Kelahiran',
            'Surat Keterangan Kematian',
            'Surat Keterangan Usaha',
            'Surat Keterangan Tidak Mampu',
            'Surat Pengantar PBB',
        ];
    }

    /**
     * Get e-tiket code for this pengajuan (instance method)
     * Returns formatted string like "SURAT-KETERANGAN-USAHA"
     * Can be called with: $pengajuan->getLayananEtiketCode()
     */
    public function getLayananEtiketCode(): string
    {
        return strtoupper(
            str_replace(' ', '-', $this->jenis_layanan ?? '')
        );
    }

    /**
     * Magic method to handle instance call layananEtiket()
     * Allows: $pengajuan->layananEtiket() to return string
     * Static call PengajuanSurat::layananEtiket() still returns array
     */
    public function __call($method, $parameters)
    {
        if ($method === 'layananEtiket' && empty($parameters)) {
            return $this->getLayananEtiketCode();
        }

        return parent::__call($method, $parameters);
    }

}

