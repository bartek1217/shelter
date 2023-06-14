<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait JsonErrorsBuilder
{
    /**
     * Make array with errors
     *
     * @param $validator
     * @return array
     */
    public function makeJsonError($validator): array
    {
        return [
            'errors' => $validator->errors()
        ];
    }
}
