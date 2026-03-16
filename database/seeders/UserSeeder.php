<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'phone' => '0501234567',
            ],
            [
                'name' => 'سارة أحمد',
                'email' => 'sara@example.com',
                'phone' => '0507654321',
            ],
            [
                'name' => 'خالد العتيبي',
                'email' => 'khaled@example.com',
                'phone' => '0555555555',
            ],
            [
                'name' => 'ليلى عبدالله',
                'email' => 'laila@example.com',
                'phone' => '0544444444',
            ],
            [
                'name' => 'فهد الشمري',
                'email' => 'fahad@example.com',
                'phone' => '0533333333',
            ],
        ];

        foreach ($customers as $customerData) {
            $user = User::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'phone' => $customerData['phone'],
                'remember_token' => Str::random(10),
            ]);

            // Add a default address for each user
            UserAddress::create([
                'user_id' => $user->id,
                'type' => 'home',
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => explode(' ', $user->name)[1] ?? '',
                'phone' => $user->phone,
                'city' => 'الرياض',
                'state' => 'منطقة الرياض',
                'address_line1' => 'شارع التخصصي، حي المعذر',
                'zip_code' => '12345',
                'is_default' => true,
            ]);

            // Add a secondary address for some users
            if ($user->id % 2 == 0) {
                UserAddress::create([
                    'user_id' => $user->id,
                    'type' => 'work',
                    'first_name' => explode(' ', $user->name)[0],
                    'last_name' => explode(' ', $user->name)[1] ?? '',
                    'phone' => $user->phone,
                    'city' => 'جدة',
                    'state' => 'مكة المكرمة',
                    'address_line1' => 'طريق الملك عبدالعزيز، حي الشاطئ',
                    'zip_code' => '54321',
                    'is_default' => false,
                ]);
            }
        }
    }
}
