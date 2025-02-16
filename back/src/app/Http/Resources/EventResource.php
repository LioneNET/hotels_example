<?php

namespace App\Http\Resources;

use App\Enums\EventTypeEnums;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read EventTypeEnums $type
 * @property-read string $details
 * @property-read Carbon $updated_at
 */
class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_name' => $this->name,
            'event' => $this->type->translate(),
            'details' => $this->details,
            'updated' => $this->updated_at->tz($request['timeZone'])->format('d.m.Y H:i:s'),
        ];
    }
}
