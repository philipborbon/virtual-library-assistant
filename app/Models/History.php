<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;
use DateTime;

class History extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'approved',
        'date_approved_at',
    ];

    protected $casts = [
        'date_approved_at' => 'datetime',
    ];

    public function setApprovedAttribute($value) 
    {
        $this->attributes['approved'] = $value;

        if ($value && $this->date_approved_at == null) {
            $this->attributes['date_approved_at'] = now();
        } else {
            $this->attributes['date_approved_at'] = null;
        }
    }

    public function getUserNameAttribute()
    {
        return $this->user->name;
    }

    public function getBookTitleAttribute()
    {
        return $this->book->title;
    }

    public function getDateRequestedAttribute()
    {
        return $this->created_at->format('F j, Y g:i A');
    }

    public function getCategoryAttribute()
    {
        return $this->book->category_name;
    }

    public function getLanguageAttribute()
    {
        return $this->book->language;
    }
    
    public function getDescriptionAttribute()
    {
        return $this->book->description;
    }
    
    public function getImageAttribute()
    {
        return $this->book->image;
    }

    public function getAuthorAttribute() 
    {
        return $this->book->author;
    }

    public function getPublisherAttribute()
    {
        return $this->book->publisher;
    }
    
    public function getDatePublishedAttribute()
    {
        return $this->book->date_published;
    }
    
    public function getPagesAttribute()
    {
        return $this->book->pages;
    }

    # Relationships

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
}
