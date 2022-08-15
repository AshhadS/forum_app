<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;


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
        //Validated
        $post_validation = Validator::make($request->all(), 
        [
            'question' => 'required',
        ]);

        if($post_validation->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Post Validation Failed',
                'errors' => $post_validation->errors()
            ], 200);
        }

        // Get Logged in user
        $request = request();
        $current_user = $request->user();
        
        // Default approval status is un approved
        $approval_status = 0;

        // Admins post are approved by defaut
        if($current_user->hasRole('admin')) {
            $approval_status = 1;
        }

        // In the future take this from the request
        $product_id = 1;

        $post = Post::create([
            'uuid' => Str::uuid()->toString(),
            'question' => $request->question,
            'created_by' => $current_user->id,
            'approved' => $approval_status,
            'product_id' => $product_id
        ]);

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
        $current_user = $request->user();

        $post_actions = [
            'can_edit' => false,
            'can_approve' => false
        ];

        // Only allow user who created the post to edit
        $is_editable = false;
        if($current_user->id == $post->created_by) {
            $post_actions['can_edit'] = true;
        }

        if($current_user->hasRole('admin')) {
            $post_actions['can_approve'] = true;
        }

        // Return the post belonging to the passed id
        return response()->json([
            'status' => true,
            'message' => 'Show selected post',
            'post' => $post,
            'post_actions' => $post_actions,
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
        //Validated
        $post_validation = Validator::make($request->all(), 
        [
            'question' => 'required',
        ]);

        if($post_validation->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Post Validation Failed',
                'errors' => $post_validation->errors()
            ], 200);
        }

        // Get Logged in user
        $request = request();
        $current_user = $request->user();

        // Check is user updating the post is the one who created it 
        if($current_user->id != $post->created_by) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry you do not have access to update this post',
            ], 200);
        }

        // Find the post and update the post
        $updated_post = Post::where('id', $post->id)->update([
            'question'=>$request->question,
            'approved'=>'0' // question change needs to again be approved
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Update Selected Post',
            'post' => $post->id,
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
        // Get Logged in user
        $request = request();
        $current_user = $request->user();

        // Check is user deleting post is the one who created it 
        if($current_user->id != $post->created_by) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry you dont have access to delete the post',
            ], 200);
        }

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
    public function approve(Request $request, Post $post)
    {
        // Get Logged in user
        $request = request();
        $current_user = $request->user();

        // Only Admins can approve posts
        if(!$current_user->hasRole('admin')) {
            return response()->json([
                'status' => false,
                'message' => 'User Not Authorized',
            ], 200);
        }

        // Approve the post
        $post->approved = 1;
        $post->save();

        return response()->json([
            'status' => true,
            'message' => 'Post Approved',
            "post" => $post->id
        ], 200);
    }

    /**
     * Get all post that are pending approval
     *
     * @return \Illuminate\Http\Response
     */
    public function pending_posts()
    {
        // Get Logged in user
        $request = request();
        $current_user = $request->user();

        // Only Admins can see pending posts
        if(!$current_user->hasRole('admin')) {
            return response()->json([
                'status' => false,
                'message' => 'User Not Authorized',
            ], 200);
        }

        // Filter for approved posts
        $approved_posts = Post::where('approved', "0")->get();

        return response()->json([
            'status' => true,
            'message' => 'Gets all un-approved posts',
            'posts' => $approved_posts->toArray(),
        ], 200);
    }

    
}
