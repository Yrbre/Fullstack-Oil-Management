<?php

namespace App\Http\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'summary' => [
                'total_item' => $this['summary']['total_item'],
                'total_consumption' => $this['summary']['total_consumption'],
                'total_receipt' => $this['summary']['total_receipt'],
            ],
        ];
    }
}
