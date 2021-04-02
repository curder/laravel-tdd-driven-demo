<?php

namespace App\Models;

use LogicException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Book
 * @property int id
 * @property string title
 * @property string author
 *
 *
 * @package App\Models
 */
class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'author_id',
    ];

    /**
     * @return string
     */
    public function path() : string
    {
        return '/books/' . $this->id;
    }

    public function setAuthorIdAttribute($author)
    {
        $this->attributes['author_id'] = (Author::firstOrCreate([
            'name' => $author,
        ]))->id;
    }


    public function reservations() : HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function checkout(User $user)
    {
        $this->reservations()->create([
           'user_id' => $user->id,
           'checked_out_at' => now(),
        ]);
    }

    public function checkin(User $user)
    {
        $reservation = $this->reservations()->where('user_id', $user->id)
            ->whereNotNull('checked_out_at')
            ->whereNull('checked_in_at')
            ->first();

        if (is_null($reservation)) {
           throw new LogicException("");
        }

        $reservation->update([
            'checked_in_at' => now(),
        ]);
    }
}
