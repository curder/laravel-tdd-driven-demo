<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_id_is_recorded()
    {
        Book::factory()->create();

        $this->assertCount(1, Book::all());

    }
}
