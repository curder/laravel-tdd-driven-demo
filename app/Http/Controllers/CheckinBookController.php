<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use LogicException;
use Symfony\Component\HttpFoundation\Response;

class CheckinBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Book $book)
    {
        try {
            $book->checkin(auth()->user());
        } catch (LogicException $e) {
          return response([], Response::HTTP_NOT_FOUND);
        }
    }
}
