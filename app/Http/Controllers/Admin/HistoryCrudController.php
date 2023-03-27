<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HistoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HistoryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HistoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\History::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/history');
        CRUD::setEntityNameStrings('history', 'history');
    }

    protected function setupListOperation()
    {
        CRUD::column('user_id');
        CRUD::column('book_id');
        CRUD::addColumn([
            'name' => 'date_approved_at',
            'label' => 'Date Approved',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
        CRUD::addColumn([
            'name' => 'created_at',
            'label' => 'Date Requested',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
    }

    protected function setupShowOperation()
    {
        CRUD::column('user_id');
        CRUD::column('book_id');

        // Book detail
        CRUD::addColumn([
            'name' => 'category',
            'type' => 'closure',
            'escaped' => false,
            'function' => function($value) {
                $category = $value->category;

                // if (strlen($description) > 180) {
                //     $description = substr($description, 0, 180) . "...";
                // }

                return nl2br($category);
            },
        ]);
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

        // --

        CRUD::addColumn([
            'name' => 'date_approved_at',
            'label' => 'Date Approved',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
        CRUD::addColumn([
            'name' => 'created_at',
            'label' => 'Date Requested',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(HistoryRequest::class);

        CRUD::field('user_id');
        CRUD::field('book_id');
        CRUD::addField([
            'name' => 'approved',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => [
                null => 'Pending',
                1 => 'Approved',
                0 => 'Denied',
            ],
            'allows_null' => false,
        ]);
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(HistoryRequest::class);

        CRUD::addField([
            'name' => 'user_name',
            'label' => 'User',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
        CRUD::addField([
            'name' => 'user_id',
            'type' => 'hidden'
        ]);
        CRUD::addField([
            'name' => 'book_title',
            'label' => 'Book',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
        CRUD::addField([
            'name' => 'book_id',
            'type' => 'hidden'
        ]);
        CRUD::addField([
            'name' => 'date_requested',
            'label' => 'Date Requested',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
        CRUD::addField([
            'name' => 'approved',
            'label' => 'Status',
            'type' => 'select_from_array',
            'options' => [
                null => 'Pending',
                1 => 'Approved',
                0 => 'Denied',
            ],
            'allows_null' => false,
        ]);
    }
}
