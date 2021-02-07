<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request as HttpRequest;
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
            'categories' => Category::whereNull('parent_id')->with('children')
                ->order('order')
                ->filter(Request::only('search', 'role', 'trashed'))
                ->get()
                ->transform(function ($category) {
                    return $category->getData(true, true);
                }),
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

    public function updateTree(HttpRequest $request)
    {
        $categories = $request->all();

        function loop($items, $parent, $callback) {
            foreach ($items as $order => $item) {
                if (count($item['children']) > 0) {
                    $callback($item, $parent, $order);
                    loop($item['children'], $item['id'], $callback);
                } else {
                    $callback($item, $parent, $order);
                }
            }
        }

        loop($categories, null, function ($item, $parent, $order) {
            $item['parent_id'] = $parent;
            $item['order'] = $order;
            // dump($item);
            $category = Category::find($item['id']);
            unset($item['id'], $item['key']);
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
