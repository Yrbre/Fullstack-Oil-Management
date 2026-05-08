<?php

namespace Database\Seeders;

use App\Models\IcTransInv;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IcTransInv::factory(10000)->create();
    }
}
