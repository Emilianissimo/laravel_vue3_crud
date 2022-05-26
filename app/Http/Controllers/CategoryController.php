<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() : array
    {
        $categories = Category::select(
            'id', 
            'title', 
            'created_at'
            )->orderBy('created_at', 'DESC')->get();
        
        return [
            'paths' => [
                'POST' => route('categories.index'),
                'PUT'  => route('categories.index') . '/{id}',
                'GET'  => route('categories.index') . '/{id}',
                'DELETE'  => route('categories.index') . '/{id}',
            ],
            'data'  => $categories,
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body'  => 'required'
        ]);

        Category::add($request->all());
        return response()->noContent(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $category = Category::find($id);
        if(!$category){
            return response(['error' => 'Not found'], 404);
        }
        $posts = $category->posts()->select(
            'id', 
            'title', 
            'body', 
            'category_id',
            'image',
            'created_at'
            )->orderBy('created_at', 'DESC')->get();

        return [
            'category' => $category,
            'posts'    => $posts
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body'  => 'required'
        ]);

        $category = Category::find($id);
        if(!$category){
            return response(['error' => 'Not found'], 404);
        }
        $category->edit($request->all());
        return response()->noContent();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $category = Category::find($id);
        if(!$category){
            return response(['error' => 'Not found'], 404);
        }
        $category->remove();
        return response()->noContent();
    }
}
