<?php

namespace App\Resources\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistItemStoreResource extends JsonResource
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
            'type'          => 'item',
            'id'            => (int)$this->id,
            'attributes'    => [
                'description'       => $this->description,
                'is_completed'      => (bool)$this->is_completed,
                'completed_at'      => $this->completed_at,
                'due'               => $this->due,
                'urgency'           => $this->urgency,
                'updated_by'        => '',
                'update_at'         => $this->update_at,
                'created_at'        => $this->created_at,
            ],
            'links'         =>[
                'self'      =>url('/checklists').'/'.$this->id
            ] 
        
    ];
    }
}