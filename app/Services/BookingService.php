<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;

class BookingService
{
    /**
     * Create a booking for an expert.
     *
     * @param int $expertId
     * @param int $userId
     * @param string $scheduledAt
     * @param int $hours
     * @return Booking
     */
    public function createBooking(int $expertId, int $userId, string $scheduledAt, int $hours)
    {
        return Booking::create([
            'expert_id' => $expertId,
            'user_id' => $userId,
            'scheduled_at' => $scheduledAt,
            'hours' => $hours,
        ]);
    }
    public function getExpertById( int $expertId)
    {
       
        $expert = User::
        where('id', $expertId)
        ->where('type', 'expert')
        ->first();

        return $expert;
    }
}