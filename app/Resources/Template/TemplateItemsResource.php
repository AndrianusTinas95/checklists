<?php

namespace App\Resources\Template;

use App\Helpers\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $time = Carbon::chDue($this->due);

        return [
            'description'   => $this->description,
            'urgency'       => $this->urgency,
            'due_interval'  => $time['interval'] ?? '',
            'due_unit'      => $time['unit'] ?? ''
        ];
    }
}