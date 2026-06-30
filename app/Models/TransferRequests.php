<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransferRequests extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transfer_requests';
    protected $fillable = [
        'item_id',
        'requested_qty',
        'source_warehouse_id',
        'destination_warehouse_id',
        'department_id',
        'requested_by',
        'status',
        'requested_date',
        'notes',
    ];

    public function item()
    {
        return $this->belongsTo(IcItemMst::class, 'item_id', 'id');
    }

    public function source_warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'source_warehouse_id', 'id');
    }

    public function destination_warehouse()
    {
        return $this->belongsTo(Warehouses::class, 'destination_warehouse_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by', 'id');
    }
}
