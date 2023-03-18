<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequestCreate;
use App\Http\Requests\UserRequestUpdate;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('library_id')->label("Library ID");
        // CRUD::column('password');
        // CRUD::column('created_at');
        // CRUD::column('updated_at');
    }

    protected function setupShowOperation()
    {
        CRUD::column('name');
        CRUD::column('library_id')->label("Library ID");
        // CRUD::column('password');
        CRUD::column('created_at');
        CRUD::column('updated_at');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequestCreate::class);

        CRUD::field('name');
        CRUD::field('library_id')->label("Library ID");
        CRUD::field('password')->type('password');
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(UserRequestUpdate::class);

        CRUD::field('name');
        CRUD::field('library_id')->label("Library ID");
        CRUD::field('password')
            ->type('password')
            ->label('Password <small>(Leave blank to retain old password)</label>');
    }
}
