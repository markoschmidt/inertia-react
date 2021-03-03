<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Redirect;

class CategoryController extends Controller
{

    /**
     * List all categories, applying filters as needed
     *
     * @return void
     */
    public function index()
    {
        return Inertia::render('Categories/Index', [
            'filters' => [],
            'categories' => fn () => Category::whereNull('parent_id')
                ->order('order')
                ->get()
                ->transform(function ($category) {
                    return $category->getData(false, true);
                }),
            'category' => fn () =>
                Request::input('category')
                ? Category::find(Request::input('category'))->getData(true, true)
                :  null,
        ]);
    }

    /**
     * Show the create form for categories
     *
     * @return void
     */
    public function create()
    {
        return Inertia::render('Categories/Create');
    }

    /**
     * Create a new category
     *
     * @return void
     */
    public function store()
    {
        Request::validate([
            'name' => ['required', 'max:50'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        Category::create([
            'title' => Request::get('title'),
            'parent_id' => Request::get('parent_id'),
        ]);

        return Redirect::route('categories.index')->with('success', 'Category created.');
    }

    /**
     * Show the edit form for a category
     *
     * @param Category $category
     * @return void
     */
    public function edit(Category $category)
    {
        return Inertia::render('Categories/Edit', [
            'category' => $category->getData(true),
            'can' => Auth::user()->can('categories.edit.' . $category->id)
        ]);
    }

    /**
     * Update a category
     *
     * @param Category $category
     * @return void
     */
    public function update(Category $category)
    {
        Request::validate([
            'title' => ['required', 'max:50'],
            'parent_id' => ['nullable', 'integer'],
        ]);

        $category->update(Request::only('title', 'parent_id'));

        return Redirect::back()->with('success', 'Category updated.');
    }

    /**
     * Update the category tree after a category is dropped to a new place
     * TODO: Allow category data updates as well, does it require any changes?
     *
     * @param HttpRequest $request
     * @return void
     */
    public function updateTree(HttpRequest $request)
    {
        $categories = $request->all();

        // Helper to loop through all nodes in a tree
        // TODO: Move this somewhere else to make it usable elsewhere
        function loop($items, $parent, $callback)
        {
            foreach ($items as $order => $item) {
                if (count($item['children']) > 0) {
                    $callback($item, $parent, $order);
                    loop($item['children'], $item['key'], $callback);
                } else {
                    $callback($item, $parent, $order);
                }
            }
        }

        // Order is just the id of the category within it's parent array
        // This should be adequate to save the category's position in the tree
        loop($categories, null, function ($item, $parent, $order) {
            $item['parent_id'] = $parent;
            $item['order'] = $order;
            // dump($item);
            $category = Category::find($item['key']);
            unset($item['key']);
            $category->update($item);
        });

        return Redirect::back()->with('success', 'Category tree updated.');
    }

    /**
     * Delete a category
     *
     * @param Category $category
     * @return void
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return Redirect::back()->with('success', 'Category deleted.');
    }

    /**
     * Restore a deleted category
     *
     * @param Category $category
     * @return void
     */
    public function restore(Category $category)
    {
        $category->restore();

        return Redirect::back()->with('success', 'Category restored.');
    }
}
