<?php

namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as FacadesRequest;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * TODO: Send bearer token for api requests
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd($req);
        $user = User::find(1);
        Auth::setUser($user);

        // Lazy
        $categories = $this->getCategories();

        // Lazy
        $category = $this->getCategory();

        $data = [
            'filters' => [],
            'categories' => FacadesRequest::all() === [] || FacadesRequest::input('categories') ? $categories() : [],
            'category' => FacadesRequest::all() === [] || FacadesRequest::input('category') ? $category() : null,
        ];

        if (FacadesRequest::bearerToken()) {
            return $data;
        }

        return Inertia::render('Categories/Index', $data);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Get categories as a category tree
     *
     * @return array
     */
    private function getCategories()
    {
        return fn () => Category::whereNull('parent_id')
        ->order('order')
        ->get()
        ->transform(function ($category) {
            return $category->getData(true, true);
        });
    }

    /**
     * Get category from request
     *
     * @return \App\Models\Category|null
     */
    private function getCategory()
    {
        return fn () =>
        FacadesRequest::input('category')
            ? Category::find(FacadesRequest::input('category'))->getData(true, true)
            : null;
    }
}
