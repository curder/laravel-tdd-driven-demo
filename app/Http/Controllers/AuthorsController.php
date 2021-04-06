<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorStoreRequest;
use App\Models\Author;

class AuthorsController extends Controller
{
    public function store(AuthorStoreRequest $request) : void
    {
        Author::create($request->all());
    }
}
