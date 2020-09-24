<?php

declare(strict_types=1);

namespace App\Entity\User;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * @ORM\Embeddable
 */
class Name
{
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $first;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private string $last;

    public function __construct(string $first, string $last)
    {
        if (empty($first) || empty($last)) {
            throw new InvalidArgumentException('First and last name should be filled.');
        }

        $this->first = $first;
        $this->last = $last;
    }

    public function getFirst(): string
    {
        return $this->first;
    }

    public function getLast(): string
    {
        return $this->last;
    }

    public function getFull(): string
    {
        return $this->first . ' ' . $this->last;
    }
}
