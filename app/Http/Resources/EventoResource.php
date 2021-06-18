<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventoResource extends JsonResource
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
            'id' => $this->id,
            'name' => ucwords($this->name),
            'description' => $this->description,
            'location' => $this->location,
            'user_id' => $this->user_id,
            'date_init' => $this->dateInit,
            'date_end' => $this->dateEnd
        ];
    }
}
