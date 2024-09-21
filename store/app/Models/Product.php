<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'category_id',
        'store_id',
        'price',
        'compare_price',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',

    ];

    protected $appends = [
        'image_url'
    ];

    protected static function booted()
    {
        static::addGlobalScope('store', new StoreScope());

        static::creating(function (Product $product) {
            $product->slug = Str::slug($product->name);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id')->withDefault([
            'name' => '-'
        ]);;
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,     // Related Table
            // we don't need to write all next details if we used the default laravel modle naming
            'product_tag',  // pivot table
            'product_id',   // FK in pivot table for the current model
            'tag_id',       // FK in pivot table for the related model
            'id',           // PK current model
            'id'            // PK related model
        );
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRHICWZcFeQ7UuaU7N30-E4Vt1GaTYIU1DIEA&s';
        }

        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
    }

    public function getSalePercentAttribute()
    {
        if (!$this->compare_price) {
            return 0;
        }

        return round(100 - (100 * $this->price / $this->compare_price), 1);
    }

    public function scopeFilter(Builder $builder, $filters)
    {
        $options = array_merge([
            'store_id' => null,
            'category_id' => null,
            'tag_id' => null,
            'status' => 'active'
        ]);

        $builder->when($options['store_id'], function ($builder, $value) {
            $builder->where('store_id', $value);
        });

        $builder->when($options['category_id'], function ($builder, $value) {
            $builder->where('category_id', $value);
        });

        $builder->when($options['tag_id'], function ($builder, $value) {
            $builder->whereExists(function ($query) use ($value) {
                $query->select(1)
                    ->from('product_id')
                    ->whereRaw('product_id as products.id')
                    ->where('tag_id');
            });
        });

        $builder->when($options['status'], function ($builder, $value) {
            $builder->where('status', $value);
        });
    }
}
