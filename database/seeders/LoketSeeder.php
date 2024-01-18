<?php

namespace Database\Seeders;

use App\Models\Loket;
use Illuminate\Database\Seeder;

class LoketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $loket1 = Loket::create(['nama' => 'Loket 1']);
        $loket2 = Loket::create(['nama' => 'Loket 2']);
        $loket3 = Loket::create(['nama' => 'Loket 3']);

        // Membuat beberapa antrian untuk masing-masing loket
        // Antrian::create([
        //     'loket_id' => $loket1->id,
        //     'nomor_antrian' => 1,
        // ]);
        // Antrian::create([
        //     'loket_id' => $loket2->id,
        //     'nomor_antrian' => 1,
        // ]);

        // Antrian::create([
        //     'loket_id' => $loket3->id,
        //     'nomor_antrian' => 1,
        // ]);
    }
}
