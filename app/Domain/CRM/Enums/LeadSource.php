<?php

namespace App\Domain\CRM\Enums;

enum LeadSource: string
{
    case Instagram = 'instagram';
    case Facebook = 'facebook';
    case Indicacao = 'indicacao';
    case WhatsApp = 'whatsapp';
    case Analise = 'analise';
    case Google = 'google';
    case Site = 'site';
    case Outro = 'outro';

    public function label(): string
    {
        return match ($this) {
            self::Instagram => 'Instagram',
            self::Facebook => 'Facebook',
            self::Indicacao => 'Indicação',
            self::WhatsApp => 'WhatsApp',
            self::Analise => 'Análise',
            self::Google => 'Google',
            self::Site => 'Site',
            self::Outro => 'Outro',
        };
    }

    public static function normalize(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $normalized = mb_strtolower(trim($value));
        $normalized = strtr($normalized, [
            'á' => 'a', 'à' => 'a', 'ã' => 'a', 'â' => 'a',
            'é' => 'e', 'ê' => 'e', 'í' => 'i',
            'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ú' => 'u', 'ç' => 'c',
        ]);
        $normalized = str_replace([' ', '-', '_ads'], ['_', '_', ''], $normalized);

        return match ($normalized) {
            'meta', 'meta_ads', 'facebook_ads', 'fb' => self::Facebook->value,
            'insta', 'instagram_ads' => self::Instagram->value,
            'indicacao', 'indicação' => self::Indicacao->value,
            'whats', 'whatsapp_business' => self::WhatsApp->value,
            'analise', 'formulario_analise' => self::Analise->value,
            'google_ads', 'google' => self::Google->value,
            'website', 'pagina', 'landing_page' => self::Site->value,
            default => in_array($normalized, array_column(self::cases(), 'value'), true)
                ? $normalized
                : self::Outro->value,
        };
    }

    public static function options(): array
    {
        return array_map(fn (self $item) => ['value' => $item->value, 'label' => $item->label()], self::cases());
    }
}
