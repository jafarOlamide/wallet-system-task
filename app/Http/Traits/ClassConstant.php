<?php

namespace App\Traits;

use ReflectionClass;

trait ClassConstant
{
    public function getConstants(): array
    {
        $reflectionClass = new ReflectionClass($this);

        return $reflectionClass->getConstants();
    }
}
