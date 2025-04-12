<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExportRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'file_path' => $this->when($this->status === 'success', $this->file_path),
            'exported_at' => $this->when($this->status === 'success', $this->updated_at->toDateTimeString()),
        ];
    }
}
