<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Hash;

class User extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'library_id',
        'password'
    ];

    public function setPasswordAttribute($value) 
    {
        $this->attributes['password'] = Hash::make($value);
    }
}
