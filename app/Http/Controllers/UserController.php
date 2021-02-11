<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{

    /**
     * List all users, applying filters as needed
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Users/Index', [
            'filters' => [],
            'users' => User::query()
                ->order('name')
                ->filter(Request::only('search', 'role', 'trashed'))
                ->paginate()
                ->transform(function ($user) {
                    return $user->getData(true);
                }),
        ]);
    }

    /**
     * Show the create form for users
     *
     * @return void
     */
    public function create()
    {
        return Inertia::render('Users/Create');
    }

    /**
     * Create a new user
     *
     * @return void
     */
    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')],
            'password' => ['nullable'],
        ]);

        User::create([
            'name' => Request::get('first_name'),
            'email' => Request::get('email'),
            'password' => Request::get('password'),
        ]);

        return Redirect::route('users.index')->with('success', 'User created.');
    }

    /**
     * Show the edit form for a user
     *
     * @param User $user
     * @return void
     */
    public function edit(User $user)
    {
        return Inertia::render('Users/Edit', [
            'user' => $user->getData(true),
        ]);
    }

    /**
     * Update a user
     *
     * @param User $user
     * @return void
     */
    public function update(User $user)
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'email' => ['required', 'max:50', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable'],
        ]);

        $user->update(Request::only('name', 'email'));

        if (Request::get('password')) {
            $user->update(['password' => Request::get('password')]);
        }

        return Redirect::back()->with('success', 'User updated.');
    }

    /**
     * Delete a user
     *
     * @param User $user
     * @return void
     */
    public function destroy(User $user)
    {
        if (App::environment('production') && $user->isDemoUser()) {
            return Redirect::back()->with('error', 'Deleting the demo user is not allowed.');
        }

        $user->delete();

        return Redirect::back()->with('success', 'User deleted.');
    }

    /**
     * Restore a deleted user
     *
     * @param User $user
     * @return void
     */
    public function restore(User $user)
    {
        $user->restore();

        return Redirect::back()->with('success', 'User restored.');
    }
}
