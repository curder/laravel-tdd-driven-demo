<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use LogicException;

/**
 * Class Book
 *
 * @property int id
 * @property string title
 * @property int author_id
 *
 * @package App\Models
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Reservation[] $reservations
 * @property-read int|null $reservations_count
 * @method static \Database\Factories\BookFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @mixin \Eloquent
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

    public function setAuthorIdAttribute($author) : void
    {
        $this->attributes['author_id'] = (Author::firstOrCreate([
            'name' => $author,
        ]))->id;
    }

    public function reservations() : HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function checkout(User $user) : void
    {
        $this->reservations()->create([
           'user_id' => $user->id,
           'checked_out_at' => now(),
        ]);
    }

    public function checkin(User $user) : void
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
