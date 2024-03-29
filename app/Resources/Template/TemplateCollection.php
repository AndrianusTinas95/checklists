<?php

namespace App\Resources\Template;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TemplateCollection extends ResourceCollection
{
    private $meta;
    private $links;

    public function __construct($request)
    {
        $this->meta = [
            'count' => $request->perPage(),
            'total' => $request->total()
        ];
        $this->links =[
            'first' =>$request->path() ."?page_offset=".$request->currentPage(),
            'last'  =>$request->path() ."?page_offset=". $request->lastPage(),
            'next'  =>$request->nextPageUrl(),
            'prev'  =>$request->previousPageUrl() 
        ];

        $resources = $request->getCollection();

        parent::__construct($resources);
    }

    public function toArray($request)
    {
        return [
            'data'  => TemplateResource::collection($this),
            'meta'  => $this->meta,
            'links' => $this->links
        ];
    }

}