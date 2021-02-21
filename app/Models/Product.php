<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description'];

    protected $fillable = ['name', 'description'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_has_products');
    }

    public function getData($addRelations = false)
    {
        $data = [
            'id' => $this->id,
            'name' => $this->getTranslations('name'),
            'description' => $this->getTranslations('description')
        ];

        if ($addRelations) {
            // TODO: Add categories to data
            $data['categories'] = $this->categories()->get()->transform(function ($category) {
                return $category->getData();
            });
        }

        return $data;
    }
}
