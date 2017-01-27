<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ComponentUnit extends Model {
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $fillable = [
        'code','unit_id','name'
    ];

    public function unit() {
        return $this->belongsTo(Unit::class);
    }
}