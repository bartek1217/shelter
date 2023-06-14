<?php

namespace App\Http\Transformers;

use Illuminate\Http\Request;
use League\Fractal\TransformerAbstract;

abstract class Transformer extends TransformerAbstract
{
    /** @var Request */
    protected Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
