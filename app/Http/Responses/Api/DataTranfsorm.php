<?php

namespace App\Http\Responses\Api;

use App\Http\Transformers\Transformer;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use League\Fractal\Manager as TransformerManager;
use League\Fractal\Resource\Collection as TransformerCollection;
use League\Fractal\Resource\Item as TransformerItem;
use League\Fractal\Resource\ResourceInterface;

class DataTranfsorm
{
    /** @var mixed */
    protected mixed $items;

    /** @var Request */
    protected Request $request;

    /** @var Transformer|null */
    protected ?Transformer $transformer;

    /**
     * @param mixed $items
     * @param Request $request
     * @param Transformer|null $transformer
     */
    public function __construct(
        mixed $items,
        Request $request,
        Transformer $transformer = null
    ) {
        $this->request = $request;
        $this->items = $items;
        $this->transformer = $transformer;
    }

    public function toArray(): array
    {
        $items = $this->items;

        return match (true) {
            $items instanceof Collection => $this->tranfsormCollection($items),
            $items instanceof Builder => $this->tranfsormBuilder($items),
            default => $this->tranfsormItem($items)
        };
    }

    /**
     * @param Builder $data
     * @return array
     */
    protected function tranfsormBuilder(Builder $data): array
    {
        $pagename = (string) $this->request->get('pagename', 'page');

        if ($this->request->has([$pagename, 'perpage'])) {
            $perpage = (int) $this->request->get('perpage');
            $page = (int) $this->request->get($pagename, 1);

            /** @see \Illuminate\Pagination\LengthAwarePaginator */
            $data = $data->paginate($perpage, ['*'], $pagename, $page);

            return [
                'data' => $this->tranfsormCollection(new Collection($data->items())),
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'to' => $data->lastItem(),
                'total' => $data->total(),
            ];
        }

        return $this->tranfsormCollection($data->get());
    }

    /**
     * @param Collection $data
     * @return array
     */
    protected function tranfsormCollection(Collection $data): array
    {
        if ($this->transformer) {
            return $this->transformerManager(new TransformerCollection($data, $this->transformer));
        }

        return $data->toArray();
    }

    /**
     * @param mixed $data
     * @return array
     */
    protected function tranfsormItem(mixed $data): array
    {
        if ($this->transformer) {
            return $this->transformerManager(new TransformerItem($data, $this->transformer));
        }

        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        return $data;
    }

    /**
     * @param ResourceInterface $resource
     * @return array
     */
    protected function transformerManager(ResourceInterface $resource): array
    {
        return (new TransformerManager)
            ->createData($resource)
            ->toArray()['data'];
    }
}
