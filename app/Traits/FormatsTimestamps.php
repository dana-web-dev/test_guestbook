<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatsTimestamps
{
    protected function formatDateTime(?string $value): ?string
    {
        return $value ? Carbon::parse($value)->format('d.m.Y H:i:s') : null;
    }

    public function getCreatedAtFormattedAttribute(): ?string
    {
        return $this->formatDateTime($this->created_at);
    }

    public function getUpdatedAtFormattedAttribute(): ?string
    {
        return $this->formatDateTime($this->updated_at);
    }
}
