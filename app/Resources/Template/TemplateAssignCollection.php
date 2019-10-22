<?php

namespace App\Resources\Template;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TemplateAssignCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return [
            'meta'      => [
                'count' => count($this['checklists']),
                'total' => count($this['checklists'])
            ],
            'data'      => TemplateAssignResource::collection($this['checklists']),
            'included'  => TemplateAssignIncludedResource::collection($this['items'])
        ];
    }

}