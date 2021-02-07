<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as SpatiePermission;
use App\Traits\HasTranslations;
use App\Traits\ModelScopes;

class Permission extends SpatiePermission
{
    use HasFactory, HasTranslations, ModelScopes;

    public $translatable = ['name'];

    public function getData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
