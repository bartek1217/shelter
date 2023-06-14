<?php

namespace App\Models;

use App\Contracts\Models\UuidContract;
use App\Traits\Uuidable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model implements UuidContract
{
    use SoftDeletes, Uuidable;

    /** @var array */
    protected $fillable = ['uuid', 'name', 'shelter_id'];

    /** @var array */
    protected $hidden = [
        'id', 'deleted_at',
    ];

    /** @var array */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
