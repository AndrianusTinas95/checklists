<?php

namespace App\Resources\Template;

use App\Helpers\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $time = Carbon::chDue($this->checklist->due);

        return [
            'name'              => $this->name,
            'checklist'         => [
                'description'   => $this->checklist->description,
                'due_interval'  => $time['interval'] ?? '',
                'due_unit'      => $time['unit'] ?? ''
            ],
            'items'             => $this->relationItems($this->items),
        ];
    }

    public function relationItems($items){
        return TemplateItemsResource::collection($items);
    }
}