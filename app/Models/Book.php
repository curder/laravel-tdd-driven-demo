<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Book
 * @property integer id
 * @property string title
 * @property string author
 *
 * @package App\Models
 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'author',
    ];
}
