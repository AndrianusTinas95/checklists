<?php

namespace App\Resources\Template;

use Illuminate\Http\Resources\Json\JsonResource;

class TemplateAssignResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "type"  => "checklists",
            "id"    => $this->id,
            "attributes"=> [
                "object_domain"     => $this->object_domain,
                "object_id"         => $this->object_id,
                "description"       => $this->description,
                "is_completed"      => (bool)$this->is_completed,
                "due"               => $this->due,
                "urgency"           => $this->urgency,
                "completed_at"      => $this->completed_at,
                "updated_by"        => "",
                "created_by"        => "",
                "created_at"        => $this->completed_at,
                "updated_at"        => $this->updated_at
            ],
            "links"=> [
                "self"=> url('checklists').'/'.$this->id
            ],
            "relationships"=> [
                "items"=> [
                "links"=> [
                    "self"=> url('checklists').'/'.$this->id .'/'."relationships/items",
                    "related"=> url('checklists').'/'.$this->id."/items"
                ],
                "data"=> TemplateItemAssignResource::collection($this->template->items)
                ]
            ]
        ];
    }
}