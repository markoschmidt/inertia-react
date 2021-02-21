<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Passthrough model
 */
class CategoryHasProducts extends Model
{
    use HasFactory;

    public function getKeyName()
    {
        return 'product_id';
    }

    public function getForeignKey()
    {
        return 'id';
    }
}
