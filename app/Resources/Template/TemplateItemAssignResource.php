<?php

namespace App\Resources\Template;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateItemAssignResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'type'  => 'items',
            'id'    => $this->id
        ];
    }
}