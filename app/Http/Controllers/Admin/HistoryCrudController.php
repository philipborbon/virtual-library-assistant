<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HistoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use App\Models\Book;

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
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }

    public function setup()
    {
        CRUD::setModel(\App\Models\History::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/history');
        CRUD::setEntityNameStrings('history', 'history');
    }

    protected function setupListOperation()
    {
        CRUD::column('book_id');
        // CRUD::addColumn([
        //     'name' => 'approved_at',
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
        CRUD::addColumn([
            'name' => 'due_at',
            'label' => 'Date Due',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
        CRUD::addColumn([
            'name' => 'returned_at',
            'label' => 'Date Returned',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
        CRUD::column('user_id');
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
        // CRUD::addColumn([
        //     'name' => 'description',
        //     'type' => 'closure',
        //     'escaped' => false,
        //     'function' => function($value) {
        //         $description = $value->description;

        //         // if (strlen($description) > 180) {
        //         //     $description = substr($description, 0, 180) . "...";
        //         // }

        //         return nl2br($description);
        //     },
        // ]);
        CRUD::addColumn([
            'name' => 'image',
            'type' => 'image',
            'disk' => 'public'
        ]);
        CRUD::column('author');
        CRUD::column('publisher');
        // CRUD::addColumn([
        //     'name' => 'date_published',
        //     'type' => 'date',
        //     'format' => 'MMMM D, Y',
        // ]);
        CRUD::column('pages');

        // --

        CRUD::addColumn([
            'name' => 'available',
            'label' => 'Total Books',
        ]);

        CRUD::addColumn([
            'name' => 'available_for_borrow',
            'label' => 'Available',
        ]);

        // --

        CRUD::addColumn([
            'name' => 'created_at',
            'label' => 'Date Requested',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
        CRUD::addColumn([
            'name' => 'denied_at',
            'label' => 'Date Denied',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
        CRUD::addColumn([
            'name' => 'approved_at',
            'label' => 'Date Approved',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
        CRUD::addColumn([
            'name' => 'due_at',
            'label' => 'Date Due',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
        CRUD::addColumn([
            'name' => 'returned_at',
            'label' => 'Date Returned',
            'type' => 'date',
            'format' => 'MMMM D, Y h:mm A',
        ]);
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(HistoryRequest::class);

        $current = $this->crud->getCurrentEntry();

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
            'name' => 'available',
            'label' => 'Total Books',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
        CRUD::addField([
            'name' => 'available_for_borrow',
            'label' => 'Available',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);

        $status = 'pending';

        if ($current->denied_at) {
            $status = 'denied';
        }

        if ($current->returned_at) {
            $status = 'returned';
        }

        if ($current->approved_at) {
            $status = 'approved';
        }

        CRUD::addField([
            'name' => 'status',
            'type' => 'select_from_array',
            'options' => [
                'denied' => 'Denied',
                'pending' => 'Pending',
                'approved' => 'Approved',
                'returned' => 'Returned',
            ],
            'allows_null' => false,
            'default' => $status,
        ]);
    }

    public function update()
    {
        $status = $this->crud->getRequest()->input('status');
        $current = $this->crud->getCurrentEntry();

        if ($status == 'approved' && $current->book->approved_at == null) {
            if ($current->book->getAvailable() <= 0) {
                throw ValidationException::withMessages(['available_for_borrow' => 'There are no books available for borrowing at the moment.']);
            }
        }

        $this->crud->setOperationSetting('strippedRequest', function($request) {
            $status = $request->input('status');

            if ($status) {
                switch ($status) {
                    case 'denied':
                        $request->request->add(['approved' => false]);
                        $request->request->add(['approved_at' => null]);
                        $request->request->add(['denied_at' => now()]);
                        $request->request->add(['returned_at' => null]);
                        $request->request->add(['due_at' => null]);
                    break;

                    case 'pending':
                        $request->request->add(['approved' => null]);
                        $request->request->add(['approved_at' => null]);
                        $request->request->add(['denied_at' => null]);
                        $request->request->add(['returned_at' => null]);
                        $request->request->add(['due_at' => null]);
                    break;

                    case 'approved':
                        $request->request->add(['approved' => true]);
                        $request->request->add(['approved_at' => now()]);
                        $request->request->add(['denied_at' => null]);
                        $request->request->add(['returned_at' => null]);

                        $user = User::find($request->input('user_id'));
                        $dueAt = now();

                        switch(strtolower($user->classification)) {
                        case 'faculty':
                        case 'staff':
                            $dueAt = now()->addYear();
                        break;

                        case 'student':
                        default:
                            $dueAt = now()->addDays(3);
                        break;
                        }

                        $request->request->add(['due_at' => $dueAt]);
                    break;

                    case 'returned':
                        $request->request->add(['returned_at' => now()]);
                    break;
                }
            }

            return $request->except([
                '_token', 
                '_method', 
                '_http_referrer', 
                '_save_action',

                'book_title',
                'user_name',
                'date_requested',
                'status',
            ]);
        });

        return $this->traitUpdate();
    }
}
