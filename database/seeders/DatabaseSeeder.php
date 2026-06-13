<?php

namespace Database\Seeders;

use App\Http\Controllers\api\MethodeController;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\MethodePembayaran;
use App\Models\Purchase;
use App\Models\Saler;
use App\Models\Status;
use App\Models\Titip;
use App\Models\TypeOperasional;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ]);
        Status::create([
            'status' => 'lunas'
        ]);
        Status::create([
            'status' => 'belum lunas'
        ]);
        Vendor::create([
            'nama' => 'Proliner',
            'alamat' => 'Jakarta'
        ]);
        Vendor::create([
            'nama' => 'Pointpro',
            'alamat' => 'Jakarta'
        ]);
        Driver::create([
            'nama' => 'Rahman',
            'telp' => '081321321321'
        ]);
        Driver::create([
            'nama' => 'Yogi',
            'telp' => '081321321321'
        ]);
        MethodePembayaran::create([
            'methode' => 'Transfer BRI'
        ]);
        MethodePembayaran::create([
            'methode' => 'Transfer BCA'
        ]);
        Customer::create([
            'nama_depan' => 'Suherman',
            'nama_belakang' => 'hartono'
        ]);
        Customer::create([
            'nama_depan' => 'Toni',
            'nama_belakang' => 'Kurniawan'
        ]);
    }
}
