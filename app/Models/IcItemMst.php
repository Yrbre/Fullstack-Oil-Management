<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IcItemMst extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;
    protected $table = 'ic_item_mst';

    protected $fillable = [
        'item_id',
        'item_no',
        'item_desc',
        'item_uom',
        'inactive_ind',
        'item_glclass',
        'item_usedby',
        'current_stock',
    ];

    protected $casts = [
        'item_id' => 'integer',
        'inactive_ind'  => 'integer',
    ];

    public function transaction()
    {
        return $this->hasMany(IcTrnasInv::class, 'item_id', 'item_id');
    }
}
