<?php
/**
 * Script untuk membuat user admin
 * Jalankan: php create_admin.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

echo "=== CREATE ADMIN USER ===\n\n";

// Input dari user
echo "Masukkan email admin: ";
$email = trim(fgets(STDIN));

echo "Masukkan password admin: ";
$password = trim(fgets(STDIN));

echo "Masukkan nama admin (default: Administrator): ";
$name = trim(fgets(STDIN)) ?: 'Administrator';

// Cek apakah email sudah ada
$existingUser = User::where('email', $email)->first();

if ($existingUser) {
    echo "\n⚠️  User dengan email '$email' sudah ada.\n";
    echo "Apakah Anda ingin mengupdate role menjadi admin? (y/n): ";
    $confirm = trim(fgets(STDIN));
    
    if (strtolower($confirm) === 'y') {
        $existingUser->update([
            'role' => 'admin',
            'password' => Hash::make($password),
        ]);
        echo "✅ User berhasil diupdate menjadi admin!\n";
        echo "   Email: {$existingUser->email}\n";
        echo "   Role: {$existingUser->role}\n";
    } else {
        echo "❌ Dibatalkan.\n";
    }
} else {
    // Buat user admin baru
    $admin = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'admin',
        'nik_or_nip' => 'ADMIN' . str_pad(User::where('role', 'admin')->count() + 1, 3, '0', STR_PAD_LEFT),
        'phone' => '-',
        'address' => 'Kelurahan Pabuaran Mekar',
    ]);
    
    echo "\n✅ Admin berhasil dibuat!\n";
    echo "   Email: {$admin->email}\n";
    echo "   Role: {$admin->role}\n";
    echo "   NIK/NIP: {$admin->nik_or_nip}\n";
}

echo "\n=== SELESAI ===\n";
echo "Sekarang Anda bisa login di: /admin/login\n";

