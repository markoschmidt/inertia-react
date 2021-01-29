<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Traits\HasTranslations;
use App\Traits\ModelScopes;

class Role extends SpatieRole
{
    use HasTranslations, ModelScopes;

    public $translatable = ['name'];


}
