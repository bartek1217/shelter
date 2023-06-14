<?php

namespace App\Contracts\Models;

interface UuidContract
{
    /**
     * @return string
     */
    public function getUuid(): string;

    /**
     * @return string
     */
    public function getUuidKeyName(): string;
}
