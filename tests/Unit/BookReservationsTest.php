<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LogicException;
use Tests\TestCase;

class BookReservationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_checked_out() : void
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);

        $reservation = Reservation::first();
        self::assertCount(1, Reservation::all());
        self::assertEquals($user->id, $reservation->user_id);
        self::assertEquals($book->id, $reservation->book_id);
        self::assertEquals(now()->format('Y-m-d H:i:s'), $reservation->checked_out_at);
    }

    /** @test */
    public function a_book_can_be_returned() : void
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();
        $book->checkout($user);

        $book->checkin($user);

        $reservation = Reservation::first();
        self::assertCount(1, Reservation::all());
        self::assertEquals($user->id, $reservation->user_id);
        self::assertEquals($book->id, $reservation->book_id);
        self::assertNotNull($reservation->checked_in_at);
        self::assertEquals(now()->format('Y-m-d H:i:s'), $reservation->checked_in_at);
    }

    /** @test */
    public function if_not_checked_out_exception_is_thrown() : void
    {
        $this->expectException(LogicException::class);
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkin($user);
    }

    /** @test */
    public function a_user_can_checkout_a_book_twice() : void
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        $book->checkout($user);
        $book->checkin($user);
        $book->checkout($user);

        $reservation = Reservation::find(2);
        self::assertCount(2, Reservation::all());
        self::assertEquals($user->id, $reservation->user_id);
        self::assertEquals($book->id, $reservation->book_id);
        self::assertNull($reservation->checked_in_at);
        self::assertEquals(now()->format('Y-m-d H:i:s'), $reservation->checked_out_at);

        $book->checkin($user);

        self::assertCount(2, Reservation::all());
        self::assertEquals($user->id, $reservation->fresh()->user_id);
        self::assertEquals($book->id, $reservation->fresh()->book_id);
        self::assertNotNull($reservation->fresh()->checked_in_at);
        self::assertEquals(now()->format('Y-m-d H:i:s'), $reservation->fresh()->checked_in_at);
    }
}
