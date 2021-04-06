<?php

namespace Tests\Feature;

use App\Models\Author;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function an_author_can_be_created() : void
    {
        $this->post('/authors', [
           'name' => 'Author Name',
           'dob' => '05/14/1988',
        ]);

        $authors = Author::all();
        self::assertCount(1, $authors);

        self::assertInstanceOf(Carbon::class, $authors->first()->dob);
        self::assertEquals('1988/14/05', $authors->first()->dob->format('Y/d/m'));
    }
}
