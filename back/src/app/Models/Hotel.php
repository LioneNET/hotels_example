<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property string $name Название отеля
 * @property string $timezone Временная зона
 * @property-read Booking|Collection $bookings Бронирования отеля
 */
class Hotel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'timezone'
    ];

    /**
     * Получить все брони на отель
     *
     * @return HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
