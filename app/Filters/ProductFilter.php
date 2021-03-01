<?php

namespace App\Filters;

use App\User;
use Illuminate\Http\Request;

class ProductFilter extends QueryFilters
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function name($term = null) {
        if ($term) {
            return $this->builder->where('name', 'ILIKE', "%$term%");
        }
    }

    public function fromPrice($term = null) {
        if ($term) {
            return $this->builder->where('price', '>=', $term);
        }
    }

    public function toPrice($term = null) {
        if ($term) {
            return $this->builder->where('price', '<=', $term);
        }
    }

    public function sort($term = null) {
        if ($term) {
            $term = explode(':', $term);

            return $this->builder->orderBy($term[0], $term[1] ?? 'asc');
        }
    }
}
