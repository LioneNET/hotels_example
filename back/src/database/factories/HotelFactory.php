<?php

namespace Database\Factories;

use App\Enums\EventTypeEnums;
use App\Enums\StatusEnums;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => "Hotel " . $this->faker->company(),
            'timezone' => $this->faker->timezone()
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function ($booking) {
        })->afterCreating(function (Hotel $hotel) {
            $guests = Guest::query()->whereDoesntHave('bookings')->get()->random(rand(2, 10));
            $user = User::all()->random(1)->first();

            foreach ($guests as $guest) {
                /** @var Booking $booking */
                $booking = $hotel->bookings()->create([
                    'hotel_id' => $hotel->id,
                    'status' => StatusEnums::PENDING->value
                ]);
                $booking->events()->create([
                    'type' => EventTypeEnums::BOOKING_CREATED->value,
                    'user_id' => $user->id,
                    'booking_id' => $booking->id,
                    'details' => null,
                    'system_generated' => true
                ]);
                $booking->status = StatusEnums::CONFIRMED->value;
                $booking->save();

                $booking_confirm = rand(0, 1);
                $booking->events()->create([
                    'type' => $booking_confirm ?
                        EventTypeEnums::GUEST_CHECKED_IN->value :
                        EventTypeEnums::BOOKING_CANCELING->value,
                    'user_id' => $user->id,
                    'booking_id' => $booking->id,
                    'details' => null,
                    'system_generated' => false
                ]);
                $booking->guests()->attach([$guest->id]);
                $booking->status = $booking_confirm ? StatusEnums::CHECKED_IN : StatusEnums::CANCELLED;
                $booking->save();
            }
        });
    }
}
