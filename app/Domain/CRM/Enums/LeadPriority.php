<?php

namespace App\Domain\CRM\Enums;

enum LeadPriority: string
{
    case Low = 'low';
    case Normal = 'normal';
    case High = 'high';
    case Urgent = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Baixa',
            self::Normal => 'Média',
            self::High => 'Alta',
            self::Urgent => 'Urgente',
        };
    }

    public static function normalize(?string $value): string
    {
        $normalized = mb_strtolower(trim((string) $value));

        return match ($normalized) {
            'medium', 'media', 'média', '' => self::Normal->value,
            'baixa' => self::Low->value,
            'alta' => self::High->value,
            'urgente' => self::Urgent->value,
            default => in_array($normalized, array_column(self::cases(), 'value'), true)
                ? $normalized
                : self::Normal->value,
        };
    }

    public static function options(): array
    {
        return array_map(fn (self $item) => ['value' => $item->value, 'label' => $item->label()], self::cases());
    }
}
