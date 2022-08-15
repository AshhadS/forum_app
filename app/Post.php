<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Comments;
use App\User;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'question', 'created_by', 'approved', 'product_id'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function comments(){
        return $this->hasMany(Comments::class, 'model_id');
    }
}