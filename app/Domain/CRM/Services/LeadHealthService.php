<?php

namespace App\Domain\CRM\Services;

use App\Domain\CRM\Models\Lead;

class LeadHealthService
{
    public function calculate(Lead $lead): array
    {
        $score = 55;
        $now = now();

        if ($lead->email) $score += 5;
        if ($lead->phone) $score += 10;
        if ($lead->owner_id) $score += 5;
        if ((float) $lead->value > 0) $score += 5;
        if ($lead->is_favorite) $score += 5;

        $lastInteraction = $lead->activities()->max('created_at');
        if ($lastInteraction) {
            $days = $now->diffInDays($lastInteraction);
            $score += match (true) {
                $days <= 1 => 15,
                $days <= 3 => 8,
                $days <= 7 => 0,
                $days <= 14 => -12,
                default => -25,
            };
        } else {
            $score -= 15;
        }

        if ($lead->next_follow_up_at) {
            if ($lead->next_follow_up_at->isPast() && ! in_array($lead->status, ['won', 'lost'], true)) {
                $score -= 20;
            } else {
                $score += 5;
            }
        }

        if ($lead->status === 'won') $score = 100;
        if ($lead->status === 'lost') $score = 0;

        $score = max(0, min(100, $score));
        $status = $score >= 80 ? 'healthy' : ($score >= 50 ? 'attention' : 'risk');

        return compact('score', 'status');
    }

    public function refresh(Lead $lead): Lead
    {
        $health = $this->calculate($lead);
        $lead->forceFill([
            'health_score' => $health['score'],
            'health_status' => $health['status'],
        ])->saveQuietly();

        return $lead->refresh();
    }
}
