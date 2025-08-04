<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Services\BookingService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    use ApiResponse;
    protected BookingService $_bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->_bookingService = $bookingService;
    }
   
    //
    public function book(Request $request)
    {
        Log::info('Booking request received', [
            'user_id' => $request->user()->id,
            'expert_id' => $request->expert_id,
            'scheduled_at' => $request->scheduled_at,
            'hours' => $request->hours,
        ]);
        // Validate the booking request
        $request->validate([
            'expert_id' => 'required|exists:users,id',
            'scheduled_at' => 'required|date_format:Y-m-d H:i|after:now',
            'hours' => 'required|integer|min:1,max:4',
        ]);

        Log::info('Booking validation passed', [
            'expert_id' => $request->expert_id,
            'scheduled_at' => $request->scheduled_at,
            'hours' => $request->hours,
        ]);

        $expert = $this->_bookingService->getExpertById($request->expert_id);

        Log::debug('Expert found, type is', [
            'expert' => $expert->type,
        ]);
        Log::info('Is Expert found', [
            'expert_id' => $expert ? $expert->id : null,
        ]);

        if(!$expert) {
            return $this->error('Expert not found', 404);
        }
        $user = $request->user();

        $booking = $this->_bookingService->createBooking(
            $expert->id,
            $user->id,
            $request->scheduled_at,
            $request->hours
        );
        // This is where you would typically interact with a Booking model

        return $this->success($booking, 'Booking created successfully');
     
    }

    public function me(Request $request)
    {
       
        // Get request params(direction and coloumn name) from URL
        $request->validate([
            'dir' => 'in:asc,desc',
            'col_name' => 'in:scheduled_at,hours,created_at,updated_at',
        ]);     

        $colName = $request->get('col_name', 'scheduled_at');
        $direction = $request->get('dir', 'desc');


        $user = $request->user();
        $bookings = Booking::where('user_id', $user->id)
            ->with(['expert:id,name'])
            ->orderBy($colName, $direction)
            ->get();

        return $this->success($bookings, 'User bookings retrieved successfully');
    }
   
}
