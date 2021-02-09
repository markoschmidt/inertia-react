<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\ModelScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, HasTranslations, ModelScopes;

    public $translatable = ['title'];

    protected $fillable = [
        'title',
        'parent_id',
        'order',
    ];

    protected $with = ['children'];

    protected $appends = ['key'];

    protected $hidden = [
        'updated_at', 'created_at'
    ];

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function getKeyAttribute()
    {
        return $this->id;
    }

    public function getData($addRelations = false, $isTree = false)
    {
        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'parent_id' => $this->parent_id,
        ];

        if ($isTree) {
            $data['key'] = $this->id;
        }

        if ($addRelations) {
            $data['children'] = $this->children()->count() ? $this->children->toQuery()->order('order')->get()->transform(function ($category) use ($isTree) {
                return $category->getData(true, $isTree);
            }) : [];
        }

        return $data;
    }
}
