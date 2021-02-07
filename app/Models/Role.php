<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use App\Traits\HasTranslations;
use App\Traits\ModelScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends SpatieRole
{
    use HasTranslations, ModelScopes, HasFactory;

    public $translatable = ['name'];

    public function getData($addRelations = false)
    {
        $roleData = [
            'id' => $this->id,
            'name' => $this->name,
        ];

        if ($addRelations) {
            $roleData['permissions'] = $this->permissions()->get()->transform(function ($permission) {
                return $permission->getData();
            });
        }

        return $roleData;

    }
}
