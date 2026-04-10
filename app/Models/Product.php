<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'stock', 'image', 'size_options', 'is_available'
    ];

    protected $casts = ['size_options' => 'array'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
}