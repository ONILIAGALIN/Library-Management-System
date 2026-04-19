<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'author_id',
        'publisher_id',
        'title',
        'isbn',
        'published_date',
        'available_copies',
        'extension'
    ];

    public function author()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function borrowRecords()
    {
        return $this->hasMany(BorrowRecord::class);
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, "book_categories", "book_id", "category_id");
    }

}
