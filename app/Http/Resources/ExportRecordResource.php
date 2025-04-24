<?php

namespace App\Http\Resources;

use App\Enums\ExportStatus;
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
            'type' => $this->type,
            'file_path' => $this->when($this->status === ExportStatus::SUCCESS, $this->file_path),
            'exported_at' => $this->when($this->status === ExportStatus::SUCCESS, $this->updated_at->toDateTimeString()),
        ];
    }
}
