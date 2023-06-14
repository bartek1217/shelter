<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static $this uuidFind(string $uuid)
 * @method static $this uuidFindOrFail(string $uuid)
 *
 * @mixin \App\Models\Model
 */
trait Uuidable
{
    /**
     * ---------------------------------------------------------------------------------------------------------------
     *                                                  Scopes
     * ---------------------------------------------------------------------------------------------------------------
     */

    /**
     * @param Builder $query
     * @param string $uuid
     * @return Model|$this
     */
    public function scopeUuidFind(Builder $query, string $uuid): static|Model
    {
        return $query->where($this->getTable() . '.' . $this->getUuidKeyName(), $uuid)->first();
    }

    /**
     * @param Builder $query
     * @param string $uuid
     * @return $this|Model
     */
    public function scopeUuidFindOrFail(Builder $query, string $uuid): static|Model
    {
        return $query->where($this->getTable() . '.' . $this->getUuidKeyName(), $uuid)->firstOrFail();
    }

    /**
     * ---------------------------------------------------------------------------------------------------------------
     */

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->getAttribute($this->getUuidKeyName());
    }

    /**
     * @return string
     */
    public function getUuidKeyName(): string
    {
        return 'uuid';
    }
}
