<?php

namespace App\Resources\History;

use Illuminate\Http\Resources\Json\ResourceCollection;

class HistoryCollection extends ResourceCollection
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
            'first' =>$request->path(),
            'last'  =>$request->path() ."?page=". $request->lastPage(),
            'next'  =>$request->nextPageUrl(),
            'prev'  =>$request->previousPageUrl() 
        ];

        $resources = $request->getCollection();

        parent::__construct($resources);
    }

    public function toArray($request)
    {
        return [
            'data'  => HistoryResource::collection($this),
            'meta'  => $this->meta,
            'links' => $this->links
        ];
    }
}