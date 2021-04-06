<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorStoreRequest;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorsController extends Controller
{
    public function store(AuthorStoreRequest $request) : void
    {
        Author::create($request->all());
    }
}
