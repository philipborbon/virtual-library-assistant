<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Storage;

class Book extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'language',
        'description',
        'image',
        'author',
        'publisher',
        'date_published',
        'pages',
    ];

    public static function boot()
    {
        parent::boot();

        static::deleting(function($value) {
            Storage::disk('public')->delete($value->image);
        });
    }

    public function setImageAttribute($value)
    {
        $this->uploadFileToDisk($value, 'image', 'public', '/');
    }

    public function getCategoryNameAttribute()
    {
        return $this->category?->full_path;
    }

    # Relationships

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
