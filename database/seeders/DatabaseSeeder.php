<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Purchase;
use App\Models\Saler;
use App\Models\Status;
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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password')
        ]);
        Customer::create([
            'nama_depan' => 'iksan',
            'nama_belakang' => 'judi'
        ]);
        Customer::create([
            'nama_depan' => 'memet',
            'nama_belakang' => 'kurniawan'
        ]);
        Customer::create([
            'nama_depan' => 'nana',
            'nama_belakang' => 'olop'
        ]);
        Customer::create([
            'nama_depan' => 'agil',
            'nama_belakang' => 'kurniawan'
        ]);
        Customer::create([
            'nama_depan' => 'iksan',
            'nama_belakang' => 'judi'
        ]);
        Customer::create([
            'nama_depan' => 'memet',
            'nama_belakang' => 'kurniawan'
        ]);
        Customer::create([
            'nama_depan' => 'nana',
            'nama_belakang' => 'olop'
        ]);
        Customer::create([
            'nama_depan' => 'agil',
            'nama_belakang' => 'kurniawan'
        ]);

        Driver::create([
            'nama' => 'ismet',
            'telp' => '123123123'
        ]);
        Driver::create([
            'nama' => 'oki',
            'telp' => '123123123'
        ]);
        Driver::create([
            'nama' => 'nazwa',
            'telp' => '123123123'
        ]);
        Driver::create([
            'nama' => 'man',
            'telp' => '123123123'
        ]);

        Vendor::create([
            'nama' => 'ismet',
            'alamat' => 'Purbalingga keras'
        ]);
        Vendor::create([
            'nama' => 'ismet',
            'alamat' => 'Banyumas metal'
        ]);

        Status::create([
            'status' => 'Lunas'
        ]);
        Status::create([
            'status' => 'Belum lunas'
        ]);

        Purchase::create([
            'vendor_id' => 1,
            'driver_id' => 1,
            'tonase' => 200,
            'harga' => 34500,
            'status_id' => 1,
            'jumlah' => 70000
        ]);
        Purchase::create([
            'vendor_id' => 2,
            'driver_id' => 2,
            'tonase' => 200,
            'harga' => 34500,
            'status_id' => 2,
            'jumlah' => 70000
        ]);

        Saler::create([
            'customer_id' => 1,
            'status_id' => 2,
            'tonase' => 15000,
            'tonase_gp' => 15000,
            'harga' => 15000,
            'harga_gp' => 15000,
            'kasbon' => 15000,
            'bongkar' => 15000,
            'mati' => 15000,
            'jumlah' => 100
        ]);
        Saler::create([
            'customer_id' => 1,
            'status_id' => 2,
            'tonase' => 14000,
            'tonase_gp' => 17000,
            'harga' => 16000,
            'harga_gp' => 13000,
            'kasbon' => 12000,
            'bongkar' => 11000,
            'mati' => 19000,
            'jumlah' => 1200
        ]);
    }
}
