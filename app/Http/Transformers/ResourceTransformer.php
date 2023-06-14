<?php

namespace App\Http\Transformers;

use App\Helpers\Arr;
use App\Models\Resource;

class ResourceTransformer extends Transformer
{
    /**
     * @param resource $resource
     * @return array
     */
    public function transform(Resource $resource): array
    {
        $item = $resource->toArray();

        if (Arr::has($item, 'attributes')) {
            $item['attributes'] = $this->transformAttributes($resource);
        }

        Arr::forget($item, ['subtype_attributes']);

        return $item;
    }

    /**
     * @param resource $resource
     * @return array
     */
    protected function transformAttributes(Resource $resource): array
    {
        /** @var \App\Models\User $user */
        $user = $this->request->user();

        if (! $user) {
            return $resource->attributes;
        }

        $levels = $user->getLevelAttributes();

        $attributes = $resource->subtypeAttributes->pluck('level', 'name');
        $attributes = $attributes->filter(fn ($level) => in_array($level, $levels));
        $attributes = $attributes->keys()->toArray();

        if (empty($attributes)) {
            return [];
        }

        return Arr::only($resource->attributes, $attributes);
    }
}
