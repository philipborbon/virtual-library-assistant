<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CategoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Category::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/category');
        CRUD::setEntityNameStrings('category', 'categories');
    }

    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('parent_id');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }

    protected function setupShowOperation()
    {
        CRUD::column('name');
        CRUD::column('path')->label('Parent');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(CategoryRequest::class);

        CRUD::field('name');

        CRUD::addField([
           'type'      => 'select',
           'name'      => 'parent_id',
           'entity'    => 'parent',
           'model'     => 'App\Models\Category',
           'attribute' => 'full_path',
           'options'   => (function ($query) {
                return $query->orderBy('path', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->get();
            }),
        ]);
    }
    
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(CategoryRequest::class);

        $current = $this->crud->getCurrentEntry();
        $childIds = $current->getChildIds();

        $excludeIds = [$current->id];
        $excludeIds = array_merge($excludeIds, $childIds);

        CRUD::field('name');

        CRUD::addField([
           'type'      => 'select',
           'name'      => 'parent_id',
           'entity'    => 'parent',
           'model'     => 'App\Models\Category',
           'attribute' => 'full_path',
           'options'   => (function ($query) use ($excludeIds) {
                return $query->whereNotIn('id', $excludeIds)
                    ->orderBy('path', 'ASC')
                    ->orderBy('name', 'ASC')
                    ->get();
            }),
        ]);
    }
}
