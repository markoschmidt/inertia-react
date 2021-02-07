<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;


class PermissionController extends Controller
{

    /**
     * List all Permissions, applying filters as needed
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Permissions/Index', [
            'filters' => [],
            'permissions' => Permission::query()
                ->order('name')
                ->filter(Request::only('search', 'permission', 'trashed'))
                ->paginate()
                ->transform(function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'deleted_at' => $permission->deleted_at,
                    ];
                }),
        ]);
    }

    /**
     * Show the create form for Permissions
     *
     * @return void
     */
    public function create()
    {
        return Inertia::render('Permissions/Create');
    }

    /**
     * Create a new Permission
     *
     * @return void
     */
    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50'],
        ]);

        Permission::create([
            'name' => Request::get('name'),
        ]);

        return Redirect::route('Permissions.index')->with('success', 'Permission created.');
    }

    /**
     * Show the edit form for a permission
     *
     * @param Permission $permission
     * @return void
     */
    public function edit(Permission $permission)
    {

        return Inertia::render('Permissions/Edit', [
            'permission' => [
                'id' => $permission->id,
                'name' => $permission->name,
                'deleted_at' => $permission->deleted_at,
                'can' => [
                    'edit_permission' => \Auth::user()->can('destroy_everything', $permission)
                ]
            ],
        ]);
    }

    /**
     * Update a Permission
     *
     * @param Permission $permission
     * @return void
     */
    public function update(Permission $permission)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
        ]);

        $permission->update(Request::only('name'));

        return Redirect::back()->with('success', 'permission updated.');
    }

    /**
     * Delete a Permission
     *
     * @param Permission $permission
     * @return void
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return Redirect::back()->with('success', 'Permission deleted.');
    }

    /**
     * Restore a deleted permission
     *
     * @param Permission $permission
     * @return void
     */
    public function restore(Permission $permission)
    {
        $permission->restore();

        return Redirect::back()->with('success', 'Permission restored.');
    }
}
