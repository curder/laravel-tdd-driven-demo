<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 * @property int id
 * @property string title
 * @property string author
 *
 *
 * @package App\Models
 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'author',
    ];

    /**
     * @return string
     */
    public function path() : string
    {
        return '/books/' . $this->id;
    }
}
