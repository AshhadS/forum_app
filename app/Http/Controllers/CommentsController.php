<?php

namespace App\Http\Controllers;

use App\Comments;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentsController extends Controller
{
    /**
     * Get comments by specified post 
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $model, $model_id)
    {

        // Get the entity dynamically based on the url model and id
        $entity = CommentsController::getEntity($model, $model_id);
        $commnts = $entity->comments();

        // No Comments found
        if($commnts->count() <= 0) {
            return response()->json([
                'status' => false,
                'message' => 'No Comments found',
                'post' => $post->id,
            ], 200);
        }

        // Return results
        return response()->json([
            'status' => true,
            'message' => 'Comments Retreived',
            'comments' => $commnts->get()->toArray(),
        ], 200);

    }

    /**
     * Save the comment.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $model, $model_id)
    {
        //Validation
        $comments_validation = Validator::make($request->all(), 
        [
            'body' => 'required',
        ]);

        if($comments_validation->fails()){
            return response()->json([
                'status' => false,
                'message' => 'Comment Validation Failed',
                'errors' => $post_validation->errors()
            ], 401);
        }

        // Get Logged in user
        $request = request();
        $current_user = $request->user();

        // Insert the comment
        $comment = Comments::create([
            'body' => $request->body,
            'created_by' => $current_user->id,
            'model' => $model,
            'model_id' => $model_id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Comment Added',
            'comment' => $comment->id,
            'post' => $post->id,
        ], 200);

    }

    /**
     * Returns an entity from the Provided Model & id.
     *
     * @param  $model    - model name
     * @param  $model_id - 
     * @return \Illuminate\Http\Response
     */
    private static function getEntity($model, $model_id) {
        switch ($model) {
            case 'post':
                return Post::find($model_id);
                break;

            // if we get a new model having comments uncommmnet and uses below code
            // hypothetically if user has comments 
            // case 'user':
            //     return User::find($model_id);
            //     break;
            
            default:
                return false;
                break;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comments $comment)
    {
        // Get Logged in user
        $request = request();
        $current_user = $request->user();

        // Check is user deleting post is the one who created it 
        if($current_user->id != $comment->created_by) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry you dont have access to delete the comment',
            ], 200);
        }

        // Soft Delete the passed post
        $comment_id = $comment->id;
        $comment->delete();

        return response()->json([
            'status' => true,
            'message' => 'Delete Comment',
            'comment' => $comment_id,
        ], 200);

    }
}
