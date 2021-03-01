<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFilters
{
    protected $data;
    protected $builder;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function apply(Builder $builder)
    {
        $this->builder = $builder;

        foreach ($this->data as $name => $value) {
            if (! method_exists($this, $name)) {
                continue;
            }

            if (strlen($value)) {
                $this->$name($value);
            } else {
                $this->$name();
            }
        }

        return $this->builder;
    }

    public function data() {
        return $this->data;
    }
}
