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
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }

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
        CRUD::addColumn([
            'name' => 'is_borrowable',
            'label' => 'Can be borrowed?',
            'type' => 'boolean',
        ]);
    }

    protected function setupShowOperation()
    {
        CRUD::column('name');
        CRUD::column('path')->label('Parent');
        CRUD::addColumn([
            'name' => 'is_borrowable',
            'label' => 'Can be borrowed?',
            'type' => 'boolean',
        ]);
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

        CRUD::addField([
            'type' => 'select_from_array',
            'name' => 'is_borrowable',
            'label' => 'Can be borrowed?',
            'options' => [
                1 => 'Yes',
                0 => 'No',
            ],
            'allows_null' => false,
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

        CRUD::addField([
            'type' => 'select_from_array',
            'name' => 'is_borrowable',
            'label' => 'Can be borrowed?',
            'options' => [
                1 => 'Yes',
                0 => 'No',
            ],
            'allows_null' => false,
        ]);
    }

    private function updateSubCategories($category, $isBorrowable)
    {   
        if ($category->categories()->exists()) {
            $category->categories()->update(['is_borrowable' => $isBorrowable]);

            foreach($category->categories as $subCategory) {
                $this->updateSubCategories($subCategory, $isBorrowable);
            }
        }
    }

    public function update()
    {
        $isBorrowable = $this->crud->getRequest()->input('is_borrowable');
        $current = $this->crud->getCurrentEntry();

        $this->updateSubCategories($current, $isBorrowable);

        return $this->traitUpdate();
    }
}
