<?php

namespace App\Traits;

/**
 * Add useful scope functions to a Model
 */
trait ModelScopes
{
    public function scopeOrder($query, $attribute, $dir = 'asc')
    {
        $query->orderBy($attribute, $dir);
    }

    public function scopeFilter($query, array $filters)
    {
        //TODO: Implementation
        return $query;

        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereRole($role);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
