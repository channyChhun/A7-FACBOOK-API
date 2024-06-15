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
            "image_post"=>$this->image_post,
            "user_id"=>$this->user->id,
            "comments"=>CommentResource::collection($this->comments)
        ];
    }
}
