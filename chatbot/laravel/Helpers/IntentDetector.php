<?php

namespace App\Chatbot\Helpers;

class IntentDetector
{
    protected $services;

    public function __construct()
    {
        $this->services = $this->getAvailableServices();
    }

    private function getAvailableServices(): array
    {
        return [
            ['name' => 'Surat Keterangan Kelahiran', 'slug' => 'kelahiran', 'url' => route('user.pengajuan.form', ['jenis' => 'kelahiran'])],
            ['name' => 'Surat Keterangan Kematian', 'slug' => 'kematian', 'url' => route('user.pengajuan.form', ['jenis' => 'kematian'])],
            ['name' => 'Surat Keterangan Usaha', 'slug' => 'usaha', 'url' => route('user.pengajuan.form', ['jenis' => 'usaha'])],
            ['name' => 'Surat Keterangan Tidak Mampu', 'slug' => 'tidak-mampu', 'url' => route('user.pengajuan.form', ['jenis' => 'tidak-mampu'])],
            ['name' => 'Surat Pengantar PBB', 'slug' => 'pbb', 'url' => route('user.pengajuan.form', ['jenis' => 'pbb'])],
        ];
    }

    /**
     * Detect user intent
     */
    public function detect(string $message): string
    {
        $messageLower = strtolower($message);

        // Check status
        $statusKeywords = [
            'status surat',
            'cek surat',
            'pengajuan saya',
            'status pengajuan',
            'cek pengajuan',
            'surat saya',
            'riwayat surat',
            'riwayat pengajuan',
            'status surat saya',
            'cek status surat',
        ];

        foreach ($statusKeywords as $keyword) {
            if (strpos($messageLower, $keyword) !== false) {
                return 'check_status';
            }
        }

        // List services
        $serviceListKeywords = [
            'layanan apa',
            'apa saja layanan',
            'daftar layanan',
            'layanan yang tersedia',
            'jenis layanan',
        ];

        foreach ($serviceListKeywords as $keyword) {
            if (strpos($messageLower, $keyword) !== false) {
                return 'list_services';
            }
        }

        return 'general';
    }

    /**
     * Get service link if message mentions a service
     */
    public function getServiceLink(string $message): ?array
    {
        $messageLower = strtolower($message);

        foreach ($this->services as $service) {
            $keywords = $this->getServiceKeywords($service['slug']);
            
            foreach ($keywords as $keyword) {
                if (strpos($messageLower, $keyword) !== false) {
                    return [
                        'text' => $service['name'],
                        'url' => $service['url'],
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Get keywords for a service
     */
    private function getServiceKeywords(string $slug): array
    {
        return match($slug) {
            'kelahiran' => ['kelahiran', 'lahir', 'akta kelahiran', 'bayi'],
            'kematian' => ['kematian', 'meninggal', 'wafat', 'akta kematian'],
            'usaha' => ['usaha', 'bisnis', 'sku', 'keterangan usaha'],
            'tidak-mampu' => ['tidak mampu', 'sktm', 'surat keterangan tidak mampu', 'keluarga kurang mampu', 'keterangan miskin'],
            'pbb' => ['pbb', 'pajak bumi', 'pajak bangunan', 'pajak pbb', 'pengantar pbb'],
            default => [],
        };
    }
}

