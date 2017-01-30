<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Unit extends Model {
    protected $fillable = [
        'name','unit'
    ];

    public function component_unit() {
        return $this->hasMany(ComponentUnit::class);
    }
}