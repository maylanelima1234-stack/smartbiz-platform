<?php

namespace App\Domain\CRM\Enums;

enum LeadStatus: string
{
    case Open = 'open';
    case Won = 'won';
    case Lost = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::Open => 'Aberto',
            self::Won => 'Ganho',
            self::Lost => 'Perdido',
        };
    }

    public static function normalize(?string $value): string
    {
        $normalized = mb_strtolower(trim((string) $value));
        return match ($normalized) {
            'aberto', '' => self::Open->value,
            'ganho', 'convertido' => self::Won->value,
            'perdido', 'nao_convertido', 'não convertido' => self::Lost->value,
            default => in_array($normalized, array_column(self::cases(), 'value'), true) ? $normalized : self::Open->value,
        };
    }

    public static function options(): array
    {
        return array_map(fn (self $item) => ['value' => $item->value, 'label' => $item->label()], self::cases());
    }
}
