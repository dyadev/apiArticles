<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Carbon;

class Article extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        /*return parent::toArray($request);*/

        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'created_at' => $this->created_at
        ];
    }

    public function with($request)
    {
        return [
            'version' => '1.0.0',
            'author_mail' => 'djougounarnaud@gmail.com',
            'author_name' => 'Djougounah Yaton Arnaud',
            'website' => 'http://mboageek.com'
        ];
    }
}
