<?php

namespace Tests\Unit;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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
