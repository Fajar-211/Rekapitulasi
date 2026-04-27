<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Driver;
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
        // Customer::create([
        //     'nama_depan' => 'iksan',
        //     'nama_belakang' => 'judi'
        // ]);
        // Customer::create([
        //     'nama_depan' => 'memet',
        //     'nama_belakang' => 'kurniawan'
        // ]);
        // Customer::create([
        //     'nama_depan' => 'nana',
        //     'nama_belakang' => 'olop'
        // ]);
        // Customer::create([
        //     'nama_depan' => 'agil',
        //     'nama_belakang' => 'kurniawan'
        // ]);
        // Customer::create([
        //     'nama_depan' => 'iksan',
        //     'nama_belakang' => 'judi'
        // ]);
        // Customer::create([
        //     'nama_depan' => 'memet',
        //     'nama_belakang' => 'kurniawan'
        // ]);
        // Customer::create([
        //     'nama_depan' => 'nana',
        //     'nama_belakang' => 'olop'
        // ]);
        // Customer::create([
        //     'nama_depan' => 'agil',
        //     'nama_belakang' => 'kurniawan'
        // ]);
        // Driver::create([
        //     'nama' => 'ismet',
        //     'telp' => '123123123'
        // ]);
        // Driver::create([
        //     'nama' => 'oki',
        //     'telp' => '123123123'
        // ]);
        // Driver::create([
        //     'nama' => 'nazwa',
        //     'telp' => '123123123'
        // ]);
        // Driver::create([
        //     'nama' => 'man',
        //     'telp' => '123123123'
        // ]);
        // Vendor::create([
        //     'nama' => 'ismet',
        //     'alamat' => 'Purbalingga keras'
        // ]);
        // Vendor::create([
        //     'nama' => 'ismet',
        //     'alamat' => 'Banyumas metal'
        // ]);
        // Status::create([
        //     'status' => 'Lunas'
        // ]);
        // Status::create([
        //     'status' => 'Belum lunas'
        // ]);
        // $harga1 = 34500;
        // $tonase1 = 200;
        // Purchase::create([
        //     'vendor_id' => 1,
        //     'driver_id' => 1,
        //     'size' => 0,
        //     'tanggal' => now(),
        //     'tonase' => $tonase1,
        //     'harga' => $harga1,
        //     'status_id' => 1,
        //     'jumlah' => $tonase1 * $harga1
        // ]);
        // $harga2 = 34500;
        // $tonase2 = 200;
        // Purchase::create([
        //     'vendor_id' => 2,
        //     'driver_id' => 2,
        //     'size' => 0,
        //     'tanggal' => now(),
        //     'tonase' => $tonase2,
        //     'harga' => $harga2,
        //     'status_id' => 2,
        //     'jumlah' => $tonase2 * $harga2
        // ]);
        // $tonase1 = 20.5;
        // $tonasegp1 = 35.6;
        // $harga1 = 15000;
        // $hargagp1 = 15000;
        // $kasbon1 = 500;
        // $bongkar1 = 4000;
        // $mati1 = 5;
        // $jumlah = (($tonase1 - $mati1) * $harga1) + ($tonasegp1 * $hargagp1);
        // $piutang = $jumlah - $kasbon1 - $bongkar1;
        // $sel1 = Saler::create([
        //     'customer_id' => 1,
        //     'status_id' => 2,
        //     'tonase' => $tonase1,
        //     'tonase_gp' => $tonasegp1,
        //     'harga' => $harga1,
        //     'harga_gp' => $hargagp1,
        //     'kasbon' => $kasbon1,
        //     'bongkar' => $bongkar1,
        //     'mati' => $mati1,
        //     'piutang' => $piutang,
        //     'jumlah' => $jumlah,
        // ]);
        // $sel1->titip()->create([
        //     'nomor' => 1,
        //     'nominal' => 2000
        // ]);
        // $type = TypeOperasional::create([
        //     'type' => 'Bongkar'
        // ]);
        // $type->operasional()->create([
        //     'jumlah' => 15000
        // ]);
        // $type->operasional()->create([
        //     'jumlah' => 30000
        // ]);
        // $type->operasional()->create([
        //     'jumlah' => 50000
        // ]);
    }
}
