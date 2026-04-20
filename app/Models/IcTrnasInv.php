<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IcTrnasInv extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'ic_trans_inv';
    protected $fillable = [
        'trans_id',
        'item_id',
        'item_no',
        'item_desc',
        'item_uom',
        'orgn_code',
        'whse_code',
        'whse_loc',
        'doc_type',
        'reason_code',
        'creation_date',
        'trans_date',
        'tgl',
        'bln',
        'thn',
        'periode',
        'trans_qty',
        'catatan',
        'bb_qty',
        'in_qty',
        'out_qty',
        'eb_qty',
        'created_by',
        'update_date',
        'update_by',
        'status',
    ];

    protected $casts = [
        'trans_id' => 'integer',
        'item_id' => 'integer',
        'creation_date' => 'datetime',
        'trans_date' => 'date',
        'trans_qty' => 'double',
        'bb_qty' => 'double',
        'in_qty' => 'double',
        'out_qty' => 'double',
        'eb_qty' => 'double',
        'update_date' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(IcItemMst::class, 'item_id', 'item_id');
    }
}
