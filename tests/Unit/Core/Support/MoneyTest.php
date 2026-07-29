<?php

namespace Tests\Unit\Core\Support;

use App\Core\Support\Money;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    #[DataProvider('brazilianValues')]
    public function test_it_parses_brazilian_values(string $input, string $decimal, string $formatted): void
    {
        $money = Money::parseBrazilian($input);

        self::assertSame($decimal, $money->decimal());
        self::assertSame($formatted, $money->format());
    }

    public static function brazilianValues(): array
    {
        return [
            ['R$ 1.300,00', '1300.00', 'R$ 1.300,00'],
            ['1300,00', '1300.00', 'R$ 1.300,00'],
            ['1300.00', '1300.00', 'R$ 1.300,00'],
            ['9,90', '9.90', 'R$ 9,90'],
            ['0', '0.00', 'R$ 0,00'],
        ];
    }
}
