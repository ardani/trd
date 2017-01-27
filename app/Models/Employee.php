<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model {
    use SoftDeletes;
    const STATUS = ['active','resign'];
    protected $fillable = [
        'name','code','phone','address','employee_type_id','status'
    ];

    public function employee_type() {
        return $this->hasOne(EmployeeType::class);
    }
}