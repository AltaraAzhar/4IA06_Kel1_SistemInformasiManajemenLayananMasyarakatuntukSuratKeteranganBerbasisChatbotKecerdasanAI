<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class PengajuanHistory extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pengajuan_history';

    protected $fillable = [
        'pengajuan_id',
        'admin_id',
        'status_lama',
        'status_baru',
        'catatan',
        'action', // process, selesai, revise
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public $timestamps = true;

    /**
     * Relationship: History belongs to PengajuanSurat
     */
    public function pengajuan()
    {
        return $this->belongsTo(PengajuanSurat::class, 'pengajuan_id');
    }

    /**
     * Relationship: History belongs to Admin (User)
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Create history log
     */
    public static function createHistory($pengajuanId, $statusLama, $statusBaru, $action, $catatan = null, $adminId = null)
    {
        return self::create([
            'pengajuan_id' => $pengajuanId,
            'admin_id' => $adminId ?? auth()->id(),
            'status_lama' => $statusLama,
            'status_baru' => $statusBaru,
            'action' => $action,
            'catatan' => $catatan,
            'created_at' => now(),
        ]);
    }
}

