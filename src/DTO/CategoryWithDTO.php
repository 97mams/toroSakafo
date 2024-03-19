<?php

namespace App\DTO;

class CategoryWithDTO
{

    public function __construct(
        private readonly int $id,
        private readonly string $name,
        private readonly int $count
    ) {
    }
}
