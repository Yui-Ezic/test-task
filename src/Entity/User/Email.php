<?php

declare(strict_types=1);

namespace App\Entity\User;

use InvalidArgumentException;

class Email
{
    private $value;

    public function __construct(string $value)
    {
        if (empty($value)) {
            throw new InvalidArgumentException('Email cannot be empty.');
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Incorrect email.');
        }

        $this->value = mb_strtolower($value);
    }

    public function isEqual(self $other): bool
    {
        return $this->getValue() === $other->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}