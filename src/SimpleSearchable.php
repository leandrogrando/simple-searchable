<?php

namespace LeandroGrando\SimpleSearchable;

use Illuminate\Database\Eloquent\Builder;

trait SimpleSearchable
{
    /**
     * @param Builder $query
     * @param String $search
     * @param String|Array $searchable
     * @return Builder
     */
    public function scopeSearch($query, $search, $searchable = null)
    {
        if (!$this->searchable && !$searchable || !$search) {
            return $query;
        }

        $searchable = $searchable ?? $this->searchable;

        if (is_string($searchable)) {
            $searchable = [$searchable];
        }

        return $query->where(function ($query) use ($search, $searchable) {
            foreach ($searchable as $field) {
                if (strpos($field, '.') !== false) {
                    $fields = explode('.', $field);
                    $field = array_pop($fields);
                    $table = implode('.', $fields);
                    $query->orWhereHas($table, function ($q) use ($field, $search) {
                        $q->where($field, 'LIKE', '%' . $search . '%');
                    });
                } else {
                    $query->orWhere($field, 'LIKE', '%' . $search . '%');
                }
            }
        });
    }
}
