<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
            return [
                "id"=>$this->id,
                "title"=>$this->title,
                "content"=>$this->content,
                "user_id"=>$this->user->id,
                "image_post"=>$this->image_post,
                // "comments"=>CommentResource::collection($this->comments)
            ];

    }
}
