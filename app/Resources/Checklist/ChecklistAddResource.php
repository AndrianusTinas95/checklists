<?php

namespace App\Resources\Checklist;

use Illuminate\Http\Resources\Json\JsonResource;

class ChecklistAddResource extends JsonResource
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
                'type'          => 'checklists',
                'id'            => (int)$this->id,
                'attributes'    => [
                    'object_domain'     => $this->object_domain,
                    'object_id'         => (string)$this->object_id,
                    'task_id'           => $this->template->id,
                    'description'       => $this->description,
                    'is_completed'      => (bool)$this->is_completed,
                    'due'               => $this->due,
                    'urgency'           => $this->urgency,
                    'completed_at'      => $this->completed_at,
                    'updated_by'        => $this->updated_by,
                    'created_by'        => $this->created_by,
                    'updated_at'        => $this->updated_at,
                    'created_at'        => $this->created_at,
                ],
                'links'         =>[
                    'self'      =>url('/checklists').'/'.$this->id
                ] 
            
        ];
    }

}