<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Get all post that have been approved
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Filter for approved posts
        $approved_posts = Post::where('approved', "1")->get();

        return response()->json([
            'status' => true,
            'message' => 'Gets all approved posts',
            'posts' => $approved_posts->toArray(),
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO Check if user is logged in 



        // TODO Get logged in user ID
        // Get logged in user ID from token
        $current_user = 2; 
        
        $post = Post::create([
            'uuid' => Str::uuid()->toString(),
            'question' => $request->question,
            'created_by' => $current_user,
            'approved' => 0 // since by default post are no approved
        ]);

        //
        return response()->json([
            'status' => true,
            'message' => 'New Post Created',
            'post' => $post->id,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {  
        $request = request();
        $u = $request->user();

        // Return the post belonging to the passed id
        return response()->json([
            'status' => true,
            'message' => 'Show selected post',
            'post' => $post,
            'user' => $u->id,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        // Find the post and update the post
        $updated_post = Post::where('id', $post->id)->update([
            'question'=>$request->question,
            'approved'=>'0' // question change needs to again be approved
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Update Selected Post',
            'post' => $updated_post->id,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        // Soft Delete the passed post
        $post_id = $post->id;
        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Delete Selected Post',
            'post' => $post_id,
        ], 200);
    }

    /**
     * Approve the Post.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function approve(Post $post)
    {
        // Get token trying to approve the post

        // Get if the token has access to approve the post

        // Approve the post

        // return the post

        //
        return response()->json([
            'status' => true,
            'message' => 'Delete Selected Post',
        ], 200);
    }


    
}
