<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'post_id',
        'user_id',
        'comments_content',
    ];

    public function Commentator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
