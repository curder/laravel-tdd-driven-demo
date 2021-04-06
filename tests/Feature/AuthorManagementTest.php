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
        $this->post('/authors', $this->data());

        $authors = Author::all();
        self::assertCount(1, $authors);

        self::assertInstanceOf(Carbon::class, $authors->first()->dob);
        self::assertEquals('1988/14/05', $authors->first()->dob->format('Y/d/m'));
    }

    /** @test */
    public function a_name_is_required(): void
    {
        $response = $this->post('/authors', array_merge($this->data(), ['name' => '']));

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function a_dob_is_required(): void
    {
        $response = $this->post('/authors', array_merge($this->data(), ['dob' => '']));

        $response->assertSessionHasErrors('dob');
    }

    /**
     * @return array
     */
    protected function data() : array
    {
        return [
            'name' => 'Author Name',
            'dob' => '05/14/1988',
        ];
    }
}
