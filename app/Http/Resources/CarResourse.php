<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CarResourse extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => (int)$this->id,
            'brand' => $this->brand??"",
            'note' => $this->note??"",
            'color' => $this->color??"",
            'year' => $this->year??"",
            'identity' => $this->identity??"",
            'end_insurance_date' => $this->end_insurance_date??"",
            'insurance_image' => $this->insurance_image??"",
            'identity_image' => $this->identity_image??"",
            'drive_image' => $this->drive_image??"",
        ];
    }
}
