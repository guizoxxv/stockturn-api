<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends QueryFilters
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function name(string $term = null): Builder
    {
        if ($term) {
            return $this->builder->where('name', 'ILIKE', "%$term%");
        }
    }

    public function sku(string $term = null): Builder
    {
        if ($term) {
            return $this->builder->where('sku', $term);
        }
    }

    public function fromPrice(string $term = null): Builder
    {
        if ($term) {
            return $this->builder->where('price', '>=', $term);
        }
    }

    public function toPrice(string $term = null): Builder
    {
        if ($term) {
            return $this->builder->where('price', '<=', $term);
        }
    }

    public function fromDate(string $term = null): Builder
    {
        if ($term) {
            return $this->builder->whereDate('created_at', '>=', $term);
        }
    }

    public function toDate(string $term = null): Builder
    {
        if ($term) {
            return $this->builder->whereDate('created_at', '<=', $term);
        }
    }

    public function sort(string $term = null): Builder
    {
        if ($term) {
            $term = explode(':', $term);

            return $this->builder->orderBy($term[0], $term[1] ?? 'asc');
        }
    }
}
