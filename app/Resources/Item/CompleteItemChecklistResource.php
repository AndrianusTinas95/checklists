<?php

namespace App\Resources\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class CompleteItemChecklistResource extends JsonResource
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
            'id'            => (int)$this->id,
            'item_id'       => (int)$this->id,
            'is_completed'  => (bool)$this->is_completed,
            'checklist_id'  => (string)$this->template->checklist->id,
        ];
    }
}