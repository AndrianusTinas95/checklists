<?php

namespace App\Resources\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistItemResource extends JsonResource
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
            'type'          => 'items',
            'id'            => (int)$this->id,
            'attributes'    => [
                'description'       => $this->description,
                'is_completed'      => (bool)$this->is_completed,
                'completed_at'      => $this->completed_at,
                'due'               => $this->due,
                'urgency'           => $this->urgency,
                'updated_by'        => $this->updated_by,
                'created_by'        => $this->created_by,
                'checklist_id'      => $this->template->checklist->id ?? null,
                'assignee_id'       => $this->assignee_id,
                'task_id'           => $this->task_id,
                'deleted_at'        => $this->deleted_at,
                'updated_at'         => $this->updated_at,
                'created_at'        => $this->created_at,
            ],
            'links'         =>[
                'self'      =>url('/checklists').'/'.$this->template->checklist->id.'/items/'.$this->id
            ] 
        
    ];
    }
}