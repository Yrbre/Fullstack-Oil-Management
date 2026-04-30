<?php

namespace App\Models;

use App\Models\IcTransInv;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IcItemMst extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;
    protected $table = 'ic_item_mst';

    const UPDATED_AT = null; // ← tidak ada updated_at
    const CREATED_AT = null; // ← tidak ada created_at
    protected $fillable = [
        'item_id',
        'item_no',
        'item_desc',
        'orgn_code',
        'item_uom',
        'inactive_ind',
        'item_glclass',
        'item_usedby',
        'current_stock',
    ];

    protected $casts = [
        'item_id' => 'integer',
        'inactive_ind'  => 'integer',
        'current_stock' => 'double',
    ];

    public function transaction()
    {
        return $this->hasMany(IcTransInv::class, 'item_id', 'id');
    }
}
