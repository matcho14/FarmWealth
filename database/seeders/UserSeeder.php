<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء مستخدم أدمن افتراضي
        User::create([
            'name'     => 'المدير العام',
            'email'    => 'matcho1419@gmail.com',
            'password' => Hash::make('marwamai1986'),
            'role'     => 'admin',
        ]);

        // إنشاء مستخدم تجريبي بصلاحيات محدودة
        User::create([
            'name'        => 'موظف تجريبي',
            'email'       => 'user@user.com',
            'password'    => Hash::make('user123'),
            'role'        => 'user',
            'permissions' => ['sheds', 'cycles', 'chart-of-accounts', 'expense-report', 'annual-report'], // صلاحيات تجريبية
        ]);
    }
}
