<?php

namespace App\Resources\Template;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateAssignIncludedResource extends JsonResource
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
            'id'    => $this->id,
            "attributes"=> [
                "description"=> $this->description,
                "is_completed"=> (bool)$this->is_completed,
                "due"               => $this->due,
                "urgency"           => $this->urgency,
                "completed_at"      => $this->completed_at,
                "updated_by"        => "",
                "user_id"           => "",
                "checklist_id"      => $this->template->checklist,
                "deleted_at"        => "",
                "created_at"        => $this->completed_at,
                "updated_at"        => $this->updated_at,
            ],
              "links"=> [
                "self"=> url('/items').'/'.$this->id
            ]
        ];
    }
}