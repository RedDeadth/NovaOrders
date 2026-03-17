<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObjects;

use InvalidArgumentException;

final class Money
{
    private function __construct(
        private readonly float $amount,
        private readonly string $currency = 'PEN'
    ) {
        if ($amount < 0) {
            throw new InvalidArgumentException('El monto no puede ser negativo');
        }
    }

    public static function create(float $amount, string $currency = 'PEN'): self
    {
        return new self($amount, $currency);
    }

    public static function zero(string $currency = 'PEN'): self
    {
        return new self(0, $currency);
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function add(Money $other): self
    {
        $this->ensureSameCurrency($other);
        return new self($this->amount + $other->amount, $this->currency);
    }

    public function subtract(Money $other): self
    {
        $this->ensureSameCurrency($other);
        $result = $this->amount - $other->amount;

        if ($result < 0) {
            throw new InvalidArgumentException('El resultado no puede ser negativo');
        }

        return new self($result, $this->currency);
    }

    public function multiply(int|float $factor): self
    {
        return new self(round($this->amount * $factor, 2), $this->currency);
    }

    public function equals(Money $other): bool
    {
        return $this->amount === $other->amount && $this->currency === $other->currency;
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency,
        ];
    }

    private function ensureSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('No se pueden operar monedas diferentes');
        }
    }
}
