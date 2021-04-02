<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Author extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'dob'];

    protected $dates = ['dob'];
}
