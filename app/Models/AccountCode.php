<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AccountCode extends Model {
    public $incrementing = false;
    protected $fillable = [
        'id','name','parent'
    ];
}