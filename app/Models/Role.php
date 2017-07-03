<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/19/17
 * Time: 9:31 PM
 */
namespace App\Models;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $fillable = ['name','display_name', 'description'];
}