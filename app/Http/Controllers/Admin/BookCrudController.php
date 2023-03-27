<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use App\Models\Category;

/**
 * Class BookCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BookCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Book::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/book');
        CRUD::setEntityNameStrings('book', 'books');
    }

    protected function setupListOperation()
    {
        CRUD::column('category_id');
        CRUD::column('title');
        CRUD::column('language');
        // CRUD::column('description');
        CRUD::addColumn([
            'name' => 'image',
            'type' => 'image',
            'disk' => 'public'
        ]);
        CRUD::column('author');
        // CRUD::column('publisher');
        // CRUD::column('date_published');
        // CRUD::column('pages');
    }

    protected function setupShowOperation()
    {
        CRUD::addColumn([
            'name' => 'category_name',
            'label' => 'Category',
            'type' => 'closure',
            'escaped' => false,
            'function' => function($value) {
                $category_name = $value->category_name;

                // if (strlen($description) > 180) {
                //     $description = substr($description, 0, 180) . "...";
                // }

                return nl2br($category_name);
            },
        ]);
        CRUD::column('title');
        CRUD::column('language');
        CRUD::addColumn([
            'name' => 'description',
            'type' => 'closure',
            'escaped' => false,
            'function' => function($value) {
                $description = $value->description;

                // if (strlen($description) > 180) {
                //     $description = substr($description, 0, 180) . "...";
                // }

                return nl2br($description);
            },
        ]);
        CRUD::addColumn([
            'name' => 'image',
            'type' => 'image',
            'disk' => 'public'
        ]);
        CRUD::column('author');
        CRUD::column('publisher');
        CRUD::addColumn([
            'name' => 'date_published',
            'type' => 'date',
            'format' => 'MMMM D, Y',
        ]);
        CRUD::column('pages');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(BookRequest::class);

        CRUD::addField([
           'type'      => 'select',
           'name'      => 'category_id',
           'entity'    => 'category',
           'model'     => 'App\Models\Category',
           'attribute' => 'full_path',
           'options'   => (function ($query) {
                $parentIds = Category::whereNotNull('parent_id')
                    ->select('parent_id')
                    ->distinct()
                    ->get();

                $parentIds = $parentIds->map(fn($data) => $data->parent_id);

                return $query->whereNotIn('id', $parentIds)
                    ->orderBy('path', 'ASC')
                    ->get();
            }),
        ]);

        CRUD::field('title');
        CRUD::field('language');
        CRUD::field('description');

        CRUD::addField([
            'name' => 'image',
            'type' => 'upload',
            'upload' => true,
        ]);

        CRUD::field('author');
        CRUD::field('publisher');
        CRUD::field('date_published');
        CRUD::field('pages');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
