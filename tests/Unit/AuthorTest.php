<?php

namespace Tests\Unit;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_date_of_birthday_is_nullable() : void
    {
        Author::factory()->create(['dob' => null]);

        self::assertCount(1, Author::all());
    }
}
