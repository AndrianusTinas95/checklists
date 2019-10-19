<?php 

namespace App\Resources\History;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
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
                'type'          => 'history',
                'id'            => (int)$this->id,
                'attributes'    => [
                    'loggable_type' => $this->loggable_type,
                    'loggable_id'   => $this->loggable_id,
                    'action'        => $this->action,
                    'kwuid'         => $this->kwuid,
                    'value'         => $this->value,
                    'updated_at'    => $this->updated_at,
                    'created_at'    => $this->created_at,
                ],
                'links'         =>[
                    'self'      =>url('/history').'/'.$this->id
                ] 
            
        ];
    }
}