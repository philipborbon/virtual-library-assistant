<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class Category extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'name',
        'parent_id',
        'path'
    ];

    public function setParentIdAttribute($value) 
    {
        $this->attributes['parent_id'] = $value;

        if ($value) {
            $this->attributes['path'] = Category::find($value)->createPath();
        }
    }

    public function getFullPathAttribute($value) 
    {
        if ($this->path) {
            return "{$this->path} > {$this->name}";
        }

        return $this->name;
    }

    # Relationships

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    # Functions

    protected function createPathHelper(array $paths, $category)
    {
        if ($category->parent) {
            $paths[] = $category->parent->name;

            return $this->createPathHelper($paths, $category->parent);
        }

        return implode(" > ", array_reverse($paths));
    }

    public function createPath()
    {
        return $this->createPathHelper([$this->name], $this);
    }

    protected function getChildIdHelper($parentId, &$childIds)
    {
        $ids = DB::table('categories')
            ->select('id')
            ->where('parent_id', $parentId)
            ->get();

        foreach ($ids as $value) {
            $childIds[] = $value->id;

            $this->getChildIdHelper($value->id, $childIds);
        }
    }

    public function getChildIds()
    {
        $childIds = array();

        $this->getChildIdHelper($this->id, $childIds);

        return $childIds;
    }
}
