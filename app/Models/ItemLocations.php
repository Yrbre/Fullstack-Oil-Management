<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemLocations extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'item_locations';
    protected $fillable = [
        'item_id',
        'warehouse_id',
        'orgn_code',
        'vendor_lot',
        'exp_date',
        'type',
        'received_date',
        'package',
        'qty_unit',
        'qty_weight'
    ];

    protected $casts = [
        'receive_date' => 'date',
        'exp_date' => 'date',
        'qty_unit' => 'double',
        'qty_weight' => 'double',
    ];

    public function item()
    {
        return $this->belongsTo(IcItemMst::class, 'item_id', 'id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'warehouse_id', 'id');
    }
}
