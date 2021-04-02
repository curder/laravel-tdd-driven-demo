<?php

namespace Tests\Unit;

use Tests\TestCase;
use LogicException;
use App\Models\User;
use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookReservationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_checked_out()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);

        $reservation = Reservation::first();
        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertEquals(now(), $reservation->checked_out_at);
    }

    /** @test */
    public function a_book_can_be_returned()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $book->checkout($user);

        $book->checkin($user);

        $reservation = Reservation::first();
        $this->assertCount(1, Reservation::all());
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertNotNull($reservation->checked_in_at);
        $this->assertEquals(now(), $reservation->checked_in_at);
    }

    /** @test */
    public function if_not_checked_out_exception_is_thrown()
    {
        $this->expectException(LogicException::class);
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkin($user);

    }
    /** @test */
    public function a_user_can_checkout_a_book_twice()
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);
        $book->checkin($user);
        $book->checkout($user);

        $reservation = Reservation::find(2);
        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, $reservation->user_id);
        $this->assertEquals($book->id, $reservation->book_id);
        $this->assertNull($reservation->checked_in_at);
        $this->assertEquals(now(), $reservation->checked_out_at);

        $book->checkin($user);

        $this->assertCount(2, Reservation::all());
        $this->assertEquals($user->id, $reservation->fresh()->user_id);
        $this->assertEquals($book->id, $reservation->fresh()->book_id);
        $this->assertNotNull($reservation->fresh()->checked_in_at);
        $this->assertEquals(now(), $reservation->fresh()->checked_in_at);
    }
}
