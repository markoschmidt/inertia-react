<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\ModelScopes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Spatie\Permission\Traits\HasPermissions;

class Category extends Model
{
    use HasFactory, HasTranslations, ModelScopes, HasPermissions;

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

    public function products()
    {
        return $this->hasManyThrough(Product::class, CategoryHasProducts::class);
    }

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
            'title' => $this->getTranslations('title'),
            'disabled' => !$this->userCanSee(),
            'canEdit' => $this->userCanEdit(),
            'isLeaf' => !(bool) $this->children()->count(),
        ];

        if ($isTree) {
            $data['key'] = $this->id;
        } else {
            $data['id'] = $this->id;
        }

        if ($addRelations) {
            if (is_array($addRelations)) {
                foreach ($addRelations as $relation) {

                    $callable = [
                        $this,
                        'get' . ucfirst($relation) . 'data'
                    ];
                    $data[$relation] = call_user_func($callable, $addRelations, $isTree);
                }
            } else {
                $category = intval(Request::input('category'));
                $arr = [];
                if ($category) {
                    $this->rootlineOfCategory($category, $arr);
                }
                $data['children'] = in_array($this->id, $arr) ? $this->getChildrenData($addRelations, $isTree) : null;
                $data['products'] = $category === $this->id ? $this->getProductsData() : null;
            }
        }

        return $data;
    }

    protected function rootlineOfCategory($category, &$arr) {
        $arr[] = $category;
        $category = Category::find($category);
        if ($category->parent_id) {
            $this->rootlineOfCategory($category->parent_id, $arr);
        }
    }

    protected function getChildrenData($addRelations, $isTree)
    {
        return $this->children()->count() ?
            $this->children()
            ->order('order')
            ->get()
            ->transform(function ($category) use ($addRelations, $isTree) {
                return $category->getData($addRelations, $isTree);
            }) : [];
    }

    protected function getProductsData()
    {
        $perPage = intval(Request::input('perPage', 16));
        return $this->products()->paginate($perPage)->transform(function ($product) {
            return $product->getData();
        });
    }

    public function userCanEdit()
    {
        if ($this->id) {
            return Auth::user()->can('categories.write.' . $this->id);
        }
        return false;
    }

    public function userCanSee()
    {
        if ($this->id) {
            return Auth::user()->can('categories.read.' . $this->id);
        }
        return false;
    }
}
