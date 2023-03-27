<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HistoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PendingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PendingCrudController extends HistoryCrudController
{
    public function setup()
    {
        CRUD::setModel(\App\Models\Pending::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/pending');
        CRUD::setEntityNameStrings('pending', 'pendings');

    }

    protected function setupListOperation()
    {
        CRUD::column('user_id');
        CRUD::column('book_id');
        // CRUD::addColumn([
        //     'name' => 'date_approved_at',
        //     'label' => 'Date Approved',
        //     'type' => 'date',
        //     'format' => 'MMMM D, Y h:mm A',
        // ]);
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
        // CRUD::addColumn([
        //     'name' => 'date_approved_at',
        //     'label' => 'Date Approved',
        //     'type' => 'date',
        //     'format' => 'MMMM D, Y h:mm A',
        // ]);
        CRUD::addColumn([
            'name' => 'created_at',
            'label' => 'Date Requested',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
    }

    protected function setupUpdateOperation()
    {
        parent::setupUpdateOperation();

        CRUD::removeSaveAction('save_and_preview');
        CRUD::removeSaveAction('save_and_edit');

        CRUD::addSaveAction([
            'name' => 'save_and_back',
            'redirect' => function($crud, $request, $itemId) {
                return 'pending';
            },
            'button_text' => trans('backpack::crud.save_action_save_and_back'),
        ]);
    }
}
