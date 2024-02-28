<?php

namespace App\Models;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'image',
        'news_content',
        'author_id',
    ];

    public function Author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function Comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'id');
    }
}