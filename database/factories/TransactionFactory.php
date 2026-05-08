<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IcTransInv>
 */
class TransactionFactory extends Factory
{
    protected $model = \App\Models\IcTransInv::class;
    public function definition(): array
    {
        $docType  = $this->faker->randomElement(['PORC', 'CONS', 'ADJI']);
        $adjType  = $docType === 'ADJI' ? $this->faker->randomElement(['PORC', 'CONS']) : null;
        $bbQty    = $this->faker->randomFloat(2, 0, 10000);
        $transQty = $this->faker->randomFloat(2, 1, 500);

        $inQty  = 0;
        $outQty = 0;

        if ($docType === 'PORC') {
            $inQty = $transQty;
        } elseif ($docType === 'CONS') {
            $outQty = $transQty;
        } elseif ($docType === 'ADJI') {
            if ($adjType === 'CONS') {
                $inQty = $transQty;
            } else {
                $outQty = $transQty;
            }
        }

        $ebQty     = $bbQty + $inQty - $outQty;
        $transDate = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'item_id'       => $this->faker->numberBetween(1, 50),
            'item_no'       => $this->faker->bothify('##-##-###-####'),
            'item_desc'     => $this->faker->words(3, true),
            'item_uom'      => $this->faker->randomElement(['KG', 'PCS', 'LTR', 'BOX']),
            'orgn_code'     => $this->faker->randomElement(['SFPL', 'FY1', 'FY2', 'FY3']),
            'whse_code'     => $this->faker->randomElement(['SF1', 'SF2']),
            'whse_loc'      => $this->faker->randomElement(['SF1 SUPPLIES', 'SF2 SUPPLIES']),
            'doc_type'      => $docType,
            'adj_type'      => $adjType,
            'trans_date'    => $transDate,
            'creation_date' => now(),
            'tgl'           => Carbon::parse($transDate)->format('d'),
            'bln'           => Carbon::parse($transDate)->format('m'),
            'thn'           => Carbon::parse($transDate)->format('Y'),
            'periode'       => Carbon::parse($transDate)->format('M Y'),
            'trans_qty'     => $transQty,
            'bb_qty'        => $bbQty,
            'in_qty'        => $inQty,
            'out_qty'       => $outQty,
            'eb_qty'        => $ebQty,
            'status'        => 'NEW',
            'catatan'       => $this->faker->sentence(),
            'created_by'    => 'seeder',
        ];
    }
}
