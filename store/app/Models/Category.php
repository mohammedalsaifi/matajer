<?php

namespace App\Models;

use App\Rules\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id'); // here we don't need to mention the forign id and primary key because we use the default naming of laravel
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id')->withDefault([
            'name' => '-prnt-'
        ]);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'image',
        'status',
        'slug'
    ];

    /* against of of $fillable
     protected $guarded = [];
    */

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }

    public static function scopeFilter(Builder $builder, $filters)
    {

        $builder->when($filters['name'] ?? false, function ($builder, $value) {
            $builder->where('categories.name', 'LIKE', "%{$value}%");
        });

        $builder->when($filters['status'] ?? false, function ($builder, $value) {
            $builder->where('categories.status', '=', $value);
        });
    }



    public static function rules($id = 0)
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                // unique:categories,name,$id
                Rule::unique('categories', 'name')->ignore($id),
                /* function ($attribute, $value, $fails) {
                    if ($value == 'laravel') {
                        $fails('This Name Is Forbidden!');
                    }
                } */

                // new Filter(['laravel', 'php']),

                'filter:php,laravel,html'


            ],

            'parent_id' => [
                'nullable',
                'int',
                'exists:categories,id'
            ],
            'image' => [
                'image' => 'max:1048576',
                'dimensions:min_width=100,min-height=100'
            ],
            'status' => 'in:active,inactive'
        ];
    }
}
