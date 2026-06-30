<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouses extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'warehouses';
    protected $fillable = [
        'name',
        'tag',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Departments::class, 'department_id', 'id');
    }

    public function item_locations()
    {
        return $this->hasMany(ItemLocations::class, 'warehouse_id', 'id');
    }

    public function source_warehouse()
    {
        return $this->hasMany(TransferRequests::class, 'source_warehouse_id', 'id');
    }

    public function destination_warehouse()
    {
        return $this->hasMany(TransferRequests::class, 'destination_warehouse_id', 'id');
    }
}
