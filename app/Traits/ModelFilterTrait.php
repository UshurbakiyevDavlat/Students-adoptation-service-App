<?php

namespace App\Traits;

use Spatie\QueryBuilder\QueryBuilder;

trait ModelFilterTrait
{
    private mixed $filteredResult;


    public function setFilters(array|string $filters): static
    {
        $this->filteredResult = QueryBuilder::for(self::class)
            ->allowedFilters($filters)
            ->paginate();

        return $this;
    }

    public function getFiltered(): mixed
    {
        return $this->filteredResult;
    }
}
