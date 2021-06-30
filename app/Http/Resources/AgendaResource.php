<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AgendaResource extends JsonResource
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
            'event_id' => $this->evento_id,
            'day' => $this->date_agenda,
            'hour_init' => $this->time_begin,
            'hour_end' => $this->time_end,
            'title' => $this->title,
            'locate' => $this->location,
            'desc' => $this->description
        ];
    }
}
