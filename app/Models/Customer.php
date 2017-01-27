<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model {
    use SoftDeletes;
    protected $fillable = [
        'name','phone','address','customer_type_id'
    ];

    public function customer_type() {
        return $this->hasOne(CustomerType::class);
    }
}