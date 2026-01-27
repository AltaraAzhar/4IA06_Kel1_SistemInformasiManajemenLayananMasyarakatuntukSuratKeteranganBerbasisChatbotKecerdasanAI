<?php

if (!function_exists('maskNIK')) {
    /**
     * Mask NIK untuk privacy (contoh: 3201012345670001 → 3201******0001)
     */
    function maskNIK($nik)
    {
        if (empty($nik) || strlen($nik) < 8) {
            return $nik;
        }
        
        // Ambil 4 digit pertama dan 4 digit terakhir
        $first = substr($nik, 0, 4);
        $last = substr($nik, -4);
        $middle = str_repeat('*', max(0, strlen($nik) - 8));
        
        return $first . $middle . $last;
    }
}

