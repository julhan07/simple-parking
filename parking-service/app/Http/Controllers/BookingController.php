<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Booking;
use Validator;

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /*
     process calculate price from start & end date
     */
    protected function calculate_total_price($star_date, $end_date)
    {
        $price = 0;
        $totalMinute = (abs(strtotime($end_date) - strtotime($star_date))) / 60;

        if ($totalMinute >= 60 && $totalMinute <= 120) {
            $price = 20;
        }

        if ($totalMinute >= 120 && $totalMinute <= 180) {
            $price = 60;
        }

        if ($totalMinute >= 180 && $totalMinute <= 240) {
            $price = 240;
        }

        if ($totalMinute > 240) {
            $price = 300;
        }

        return $price;
    }

    /*
    count active booking status
    */
    protected function count_on_booking()
    {
        return Booking::where('booking_status', 'ON')->get()->count();
    }

    /*
    check availability status booking
    */
    public function get_check_availability()
    {
        $count = $this->count_on_booking();
                
        if ($count <= 3) {
            return response([
                'error' => "sorri, fully booked"
            ], 400);
        }

        return response([
                'msg' => "Ready To Booking"
            ], 200);
    }


    /*
        process store booking parking
    */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'flat_number' => 'required',
            'booking_start' => 'required|date',
            'booking_end' => 'required|date'
         ]);

        if ($validator->fails()) {
            return response([
                'error' => $validator->errors()
            ], 400);
        }

        $booking_start = $request->input('booking_start');
        $booking_end = $request->input('booking_end');

        $totalPrice = $this->calculate_total_price($booking_start, $booking_end);

        $check_availability = $this->count_on_booking();

        if ($check_availability <=3) {
            return response([
                'error' => "sorri, fully booked"
            ], 400);
        }


        if ($request->input('booking_start') > $request->input('booking_end')) {
            return response([
                'error' => "booking start mush be smaller than booking_end"
            ], 400);
        }

        $data = [
            'flat_number' => $request->input('flat_number'),
            'pay_status' => "UNPAID",
            'booking_status' => "ON",
            'total_price' => $totalPrice,
            'booking_start' => $request->input('booking_start'),
            'booking_end' => $request->input('booking_end'),
        ];

        $status = Booking::create($data);

        if (!$status) {
            return response($status, 400);
        }

        return response([
            'status' => 'booked',
            
        ], 201);
    }

    /*
        check total price by start & end date
    */

    public function get_calculate_price(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_start' => 'required|date',
            'booking_end' => 'required|date'
         ]);

        if ($validator->fails()) {
            return response([
                'error' => $validator->errors()
            ], 400);
        }
        
        $booking_start = $request->input('booking_start');
        $booking_end = $request->input('booking_end');

        $totalPrice = $this->calculate_total_price($booking_start, $booking_end);

        if (!$totalPrice) {
            return response('err', 'invalid operation DB');
        }

        return response([
            'total_price' => $totalPrice,
        ], 200);
    }

    /*
        process update booking table / pay proccess
    */
    public function update_pay($id)
    {
        $data = Booking::where("id", $id)->get();

        if (!$data) {
            return response('err', 'invalid booking id');
        }

        $new_data = [
            'booking_status' => 'OFF',
            'pay_status' => 'PAID'
        ];
        
        $status = Booking::where("id", $id)->update($new_data);

        if (!$status) {
            return response([
                'error'=> "invalid update payment",
            ], 400);
        }

        return response([
                'msg'=> "success",
            ], 200);
    }
}