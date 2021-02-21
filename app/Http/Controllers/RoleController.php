<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;


class RoleController extends Controller
{

    /**
     * List all Roles, applying filters as needed
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Roles/Index', [
            'filters' => [],
            'roles' => Role::query()
                ->order('name')
                ->filter(Request::only('search', 'role', 'trashed'))
                ->paginate()
                ->transform(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->getTranslations('name'),
                        'deleted_at' => $role->deleted_at,
                    ];
                }),
        ]);
    }

    /**
     * Show the create form for Roles
     *
     * @return void
     */
    public function create()
    {
        return Inertia::render('Roles/Create');
    }

    /**
     * Create a new Role
     *
     * @return void
     */
    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50'],
        ]);

        Role::create([
            'name' => Request::get('name'),
        ]);

        return Redirect::route('Roles.index')->with('success', 'Role created.');
    }

    /**
     * Show the edit form for a role
     *
     * @param Role $role
     * @return void
     */
    public function edit(Role $role)
    {
        return Inertia::render('Roles/Edit', [
            'role' => $role->getData(true),
        ]);
    }

    /**
     * Update a Role
     *
     * @param Role $role
     * @return void
     */
    public function update(Role $role)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
        ]);

        $role->update(Request::only('name'));

        return Redirect::back()->with('success', 'role updated.');
    }

    /**
     * Delete a Role
     *
     * @param Role $role
     * @return void
     */
    public function destroy(Role $role)
    {
        $role->delete();

        return Redirect::back()->with('success', 'Role deleted.');
    }

    /**
     * Restore a deleted role
     *
     * @param Role $role
     * @return void
     */
    public function restore(Role $role)
    {
        $role->restore();

        return Redirect::back()->with('success', 'Role restored.');
    }
}
