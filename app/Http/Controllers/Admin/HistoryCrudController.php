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
        CRUD::column('category');
        CRUD::column('language');
        CRUD::addColumn([
            'name' => 'circulation',
            'label' => 'Section',
        ]);
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

        CRUD::addColumn([
            'name' => 'user_active_borrow',
            'label' => 'Unreturned By User',
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
            'name' => 'category',
            // 'label' => 'Book',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
        CRUD::addField([
            'name' => 'circulation',
            'label' => 'Section',
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
        CRUD::addField([
            'name' => 'user_active_borrow',
            'label' => 'Unreturned By User',
            'type' => 'text',
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);

        $status = 'pending';

        if ($current->denied_at) {
            $status = 'denied';
        }

        if ($current->approved_at) {
            $status = 'approved';
        }

        if ($current->returned_at) {
            $status = 'returned';
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

        if ($status == 'approved' && $current->approved_at == null) {
            if (! $current->book->canBeBorrowed()) {
                throw ValidationException::withMessages(['category' => 'Books under this category is not allowed to be borrowed.']);
            }

            if ($current->book->getAvailable() <= 0) {
                throw ValidationException::withMessages(['available_for_borrow' => 'There are no books available for borrowing at the moment.']);
            }

            if ($current->user->isBorrowLimitReached()) {
                throw ValidationException::withMessages(['user_active_borrow' => 'The student has reached his/her maximum number of books allowed to be borrowed.']);
            }
        }

        if ($status == 'returned' && $current->approved_at == null) {
            throw ValidationException::withMessages(['status' => 'Status update failed. Only approved request can be set to returned.']);
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
                            $dueAt = now()->addWeekdays(3);
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
                'category',
                'circulation',
                'user_name',
                'date_requested',
                'status',
                'available',
                'available_for_borrow',
                'user_active_borrow',
            ]);
        });

        return $this->traitUpdate();
    }
}
