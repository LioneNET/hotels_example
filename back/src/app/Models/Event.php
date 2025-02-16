<?php

namespace App\Models;

use App\Enums\EventTypeEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $type Тип события
 * @property int $user_id ИД пользователя
 * @property int $booking_id ИД брони
 * @property array $details Дополнительные данные
 * @property bool $system_generated Флаг системного события
 * @property-read User $user Объект пользователя
 * @property-read Booking $booking Объект события
 */
class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'user_id',
        'booking_id',
        'details',
        'system_generated'
    ];

    protected $casts = [
        'type' => EventTypeEnums::class
    ];

    protected $dates = [
        'updated_at'
    ];

    /**
     * Пользователь системы
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Событие
     *
     * @return BelongsTo
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
