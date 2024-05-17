<?php

namespace App\Models;

use App\Models\Category;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable =['name','slug','price','image','description','featured','active','quantity','category_id'];

    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeByCategory($query, $categoryId)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        if ($minPrice) {
            $query = $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query = $query->where('price', '<=', $maxPrice);
        }
        return $query;
    }

    public function scopeSearch($query, $key)
    {
        if ($key) {
            return $query->where('name', 'like', "%$key%");
        }
        return $query;
    }

    public function scopeSort($query, $sortKey)
    {
        switch ($sortKey) {
            case 'newest':
                return $query->orderBy('created_at', 'desc');
            case 'highest':
                return $query->orderBy('price', 'desc');
            case 'lowest':
                return $query->orderBy('price', 'asc');
            default:
                return $query;
        }
    }

}
