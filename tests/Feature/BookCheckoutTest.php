<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class BookCheckoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_book_can_be_checked_out_by_a_signed_in_user(): void
    {
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();

        $this->actingAs($user = User::factory()->create())
            ->post('/checkout/' . $book->id);

        $reservation = Reservation::first();
        self::assertCount(1, Reservation::all());
        self::assertEquals($user->id, $reservation->user_id);
        self::assertEquals($book->id, $reservation->book_id);
        self::assertEquals(now()->format('Y-m-d H:i:s'), $reservation->checked_out_at);
    }

    /** @test */
    public function only_signed_user_can_be_checkout_book(): void
    {
        $book = Book::factory()->create();

        $this->post('/checkout/' . $book->id)
             ->assertRedirect('/login');

        self::assertCount(0, Reservation::all());
    }

    /** @test */
    public function only_real_book_can_be_checkout(): void
    {
        $this->actingAs($user = User::factory()->create())
             ->post('/checkout/123')
             ->assertStatus(Response::HTTP_NOT_FOUND);

        self::assertCount(0, Reservation::all());
    }

    /** @test */
    public function a_book_can_be_checked_in_by_a_signed_in_user(): void
    {
        $book = Book::factory()->create();
        $this->actingAs($user = User::factory()->create())
             ->post('/checkout/' . $book->id);

        $this->actingAs($user)
             ->post('/checkin/' . $book->id);

        $reservation = Reservation::first();
        self::assertCount(1, Reservation::all());
        self::assertEquals($user->id, $reservation->user_id);
        self::assertEquals($book->id, $reservation->book_id);
        self::assertEquals(now()->format('Y-m-d H:i:s'), $reservation->checked_out_at);
        self::assertEquals(now()->format('Y-m-d H:i:s'), $reservation->checked_in_at);
    }

    /** @test */
    public function only_signed_user_can_be_checkin_book(): void
    {
        $book = Book::factory()->create();
        $this->actingAs(User::factory()->create())
             ->post('/checkout/' . $book->id);

        \Auth::logout();

        $this->post('/checkin/' . $book->id)
             ->assertRedirect('/login');

        self::assertCount(1, Reservation::all());
        self::assertNull(Reservation::first()->checked_in_at);
    }

    /** @test */
    public function only_real_book_can_be_checkin(): void
    {
        $this->actingAs($user = User::factory()->create())
             ->post('/checkin/123')
             ->assertStatus(Response::HTTP_NOT_FOUND);

        self::assertCount(0, Reservation::all());
    }

    /** @test */
    public function a_404_is_thrown_if_a_book_is_not_checked_out_first(): void
    {
        $this->withoutExceptionHandling();

        $book = Book::factory()->create();
        $user = User::factory()->create();

        $this->actingAs($user)
             ->post('/checkin/' . $book->id)
             ->assertStatus(Response::HTTP_NOT_FOUND);

        self::assertCount(0, Reservation::all());
    }
}
