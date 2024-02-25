<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'news_content',
        'author_id',
    ];

    public function Author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}