<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Departments extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'departments';
    protected $fillable = [
        'name',
        'code',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'department_id', 'id');
    }

    public function warehouses()
    {
        return $this->hasMany(Warehouses::class, 'department_id', 'id');
    }

    public function transfer_requests()
    {
        return $this->hasMany(TransferRequests::class, 'department_id', 'id');
    }
}
