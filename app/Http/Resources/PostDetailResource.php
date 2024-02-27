<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'news_content' => $this->news_content,
            'created_at' => date_format($this->created_at, "Y/m/d H:i:s"),
            'author_id' => $this->author_id,
            'writer' => $this->whenLoaded('Author'),
            'comments' => $this->whenLoaded('Comments', function () {
                return $this->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'post_id' => $comment->post_id,
                        'user_id' => $comment->user_id,
                        'comments_content' => $comment->comments_content,
                        'commentator' => [
                            'id' => $comment->Commentator->id,
                            'email' => $comment->Commentator->email,
                            'username' => $comment->Commentator->username,
                        ],
                    ];
                });
            }),
            'comment_total' => $this->whenLoaded('Comments', function () {
                return $this->comments->count();
            })
        ];
    }
}
