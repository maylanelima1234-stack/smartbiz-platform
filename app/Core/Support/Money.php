<?php

namespace App\Core\Support;

use InvalidArgumentException;
use Stringable;

final readonly class Money implements Stringable
{
    public function __construct(private int $cents)
    {
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public static function fromCents(int $cents): self
    {
        return new self($cents);
    }

    public static function fromDecimal(int|float|string|null $value): self
    {
        if ($value === null || $value === '') {
            return self::zero();
        }

        if (is_int($value)) {
            return new self($value * 100);
        }

        if (is_float($value)) {
            return new self((int) round($value * 100));
        }

        $normalized = trim($value);

        if ($normalized === '') {
            return self::zero();
        }

        if (! preg_match('/^-?\d+(?:\.\d{1,2})?$/', $normalized)) {
            throw new InvalidArgumentException('Valor monetário decimal inválido.');
        }

        $negative = str_starts_with($normalized, '-');
        $normalized = ltrim($normalized, '-');
        [$whole, $fraction] = array_pad(explode('.', $normalized, 2), 2, '');
        $fraction = str_pad(substr($fraction, 0, 2), 2, '0');
        $cents = ((int) $whole * 100) + (int) $fraction;

        return new self($negative ? -$cents : $cents);
    }

    public static function parseBrazilian(int|float|string|null $value): self
    {
        if ($value === null || $value === '') {
            return self::zero();
        }

        if (is_int($value) || is_float($value)) {
            return self::fromDecimal($value);
        }

        $normalized = trim($value);
        $normalized = preg_replace('/[^0-9,.-]/', '', $normalized) ?? '';

        if ($normalized === '') {
            throw new InvalidArgumentException('Valor monetário brasileiro inválido.');
        }

        $negative = str_contains($normalized, '-');
        $normalized = str_replace('-', '', $normalized);

        if (str_contains($normalized, ',')) {
            $normalized = str_replace('.', '', $normalized);
            $normalized = str_replace(',', '.', $normalized);
        } elseif (substr_count($normalized, '.') > 1) {
            $normalized = str_replace('.', '', $normalized);
        }

        $money = self::fromDecimal($normalized);

        return $negative ? new self(-abs($money->cents())) : $money;
    }

    public function cents(): int
    {
        return $this->cents;
    }

    public function decimal(): string
    {
        $absolute = abs($this->cents);
        $value = sprintf('%d.%02d', intdiv($absolute, 100), $absolute % 100);

        return $this->cents < 0 ? "-{$value}" : $value;
    }

    public function format(string $currency = 'R$'): string
    {
        $formatted = number_format(abs($this->cents) / 100, 2, ',', '.');
        $prefix = $this->cents < 0 ? '-' : '';

        return trim("{$prefix}{$currency} {$formatted}");
    }

    public function __toString(): string
    {
        return $this->format();
    }
}
