<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

use Hash;

class User extends Model
{
    use CrudTrait, HasApiTokens;

    protected $fillable = [
        'name',
        'library_id',
        'classification',
        'password'
    ];

    public function setPasswordAttribute($value) 
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getActiveBorrowAttribute()
    {
        return History::where('user_id', $this->id)
            ->where('approved', true)
            ->whereNull('returned_at')
            ->count();
    }

    public function isBorrowLimitReached()
    {
        return strtolower($this->classification) == 'student' && $this->active_borrow >= 3;
    }
}
