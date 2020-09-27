<?php


namespace App\Tests\Unit\Entity\User;

use App\Entity\User\Name;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase
{
    public function testSuccess(): void
    {
        $name = new Name($first = 'first', $last = 'last');

        self::assertEquals($first, $name->getFirst());
        self::assertEquals($last, $name->getLast());
        self::assertEquals($first . ' ' . $last, $name->getFull());
    }

    public function testEmptyFirstName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Name('', 'last');
    }

    public function testEmptyLastName(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Name('first', '');
    }
}