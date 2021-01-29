<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Traits\HasTranslations;

class Permission extends SpatiePermission
{
    use HasFactory, HasTranslations;

    public $translatable = ['name'];
}
