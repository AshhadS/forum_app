<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Post;
use App\User;

class Comments extends Model
{
    //

	protected $fillable = [
	    'body', 'model', 'model_id', 'created_by'
	];

	public function post(){
        return $this->belongsTo(Post::class, 'model_id');
    }

    public function user(){
        return $this->hasMany(User::class, 'created_by');
    }
}
