<?php

namespace App\Resources\Item;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource  extends JsonResource
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
            'id'                => (string)$this->id,
            'name'              => (string)$this->template->name,
            'user_id'           => (string)$this->user_id,
            'is_completed'      => (bool)$this->is_completed,
            'due'               => $this->due,
            'urgency'           => $this->urgency,
            'checklist_id'      => $this->template->checklist->id,
            'assignee_id'       => $this->template->id,
            'task_id'           => (string)$this->task_id,
            'completed_at'      => $this->completed_at,
            'last_update_by'    => $this->updated_by,
            'update_at'         => $this->update_at,
            'created_at'        => $this->created_at,
        ];
    }
}