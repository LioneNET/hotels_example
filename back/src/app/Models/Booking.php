<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $status Статус брони
 * @property integer $hotel_id ИД отеля
 * @property-read Hotel $hotel Объект отеля
 * @property-read Event|Collection $events События у бронирования
 * @property-read Guest|Collection $guests Гости
 */
class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'hotel_id'
    ];

    /**
     * Отель относящийся к бронированию
     *
     * @return BelongsTo
     */
    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    /**
     * События
     *
     * @return HasMany
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    /**
     * Гости относящиеся к текущему бронированию
     *
     * @return BelongsToMany
     */
    public function guests(): BelongsToMany
    {
        return $this->belongsToMany(Guest::class, 'guest_booking');
    }
}
