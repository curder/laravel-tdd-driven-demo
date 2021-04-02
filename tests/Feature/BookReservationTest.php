<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class BookReservationTest
 *
 * @package Tests\Feature
 */
class BookReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_add()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => 'Curder',
        ])->assertOk();

        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function a_title_is_required()
    {
        $this->post('/books', [
            'title' => '',
            'author' => 'Curder',
        ])->assertSessionHasErrors('title');
    }

    /** @test */
    public function a_author_is_required()
    {
        $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => '',
        ])->assertSessionHasErrors('author');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post('/books', [
               'title' => 'Cool Book Title',
               'author' => 'Curder',
           ]);

       $book = Book::first();

       $this->patch('/books/' . $book->id, [
           'title' => 'New Title',
           'author' => 'New Author',
       ]);

       $this->assertEquals('New Title', $book->fresh()->title);
       $this->assertEquals('New Author', $book->fresh()->author);
    }
}
