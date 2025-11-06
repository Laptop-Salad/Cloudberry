<?php

namespace App\Enums;

trait HasDisplay
{
    public function display(): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $this->name)));
    }
}
