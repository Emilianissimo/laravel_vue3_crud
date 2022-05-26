<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): array
    {
        $posts = Post::select(
            'id', 
            'title', 
            'body', 
            'category_id',
            'image',
            'created_at'
            )->with(['category' => function ($category){
                $category->select('id', 'title');
            }])->orderBy('created_at', 'DESC')->get();

        return [
            'paths' => [
                'POST' => route('posts.index'),
                'PUT'  => route('posts.index') . '/{id}',
                'GET'  => route('posts.index') . '/{id}',
                'DELETE'  => route('posts.index') . '/{id}',
            ],
            'data'  => $posts,
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

        try{
            $post = Post::add($request->all());
            $post->setCategory($request->category_id);
            $post->uploadImage($request->file('image'));
            return response()->json($post);
        }catch(\Exception $e){
            return response()->json([
                'type'  => get_class($e),
                'error' =>$e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $post = Post::select(
            'id', 
            'title', 
            'body', 
            'category_id',
            'image',
            'created_at',
            )
            ->with(['category' => function ($category){
                $category->select('id', 'title');
            }])->find($id);
        if(!$post){
            return response(['error' => 'Not found'], 404);
        }
        return $post->toArray();
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

        $post = Post::find($id);
        if(!$post){
            return response(['error' => 'Not found'], 404);
        }
        $post->edit($request->all());
        $post->setCategory($request->category_id);
        $post->uploadImage($request->file('image'));

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
        $post = Post::find($id);
        if(!$post){
            return response(['error' => 'Not found'], 404);
        }
        $post->remove();
        return response()->noContent();
    }
}
