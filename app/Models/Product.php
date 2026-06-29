<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model {
    protected $fillable = ['category_id', 'name', 'description', 'price', 'stock', 'image'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function getImageUrlAttribute() {
        if ($this->image && Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }
        return 'https://placehold.co/400x300/4f46e5/ffffff?text=No+Image';
    }
}