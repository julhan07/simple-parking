<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class BookingControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // test : booking/store : POST
    public function testStore()
    {
        $data = [
            "flat_number" => "500121",
            "booking_start" => "2022/11/11 01:12",
            "booking_end" => "2022/11/11 08:12"
        ];

        $this->json('POST', '/booking/store', $data)
             ->seeJsonEquals([
                'created' => true,
             ]);
    }

    // test : /booking/calculate-price : POST
    public function testGetCalculatePrice()
    {
        $data = [
            "booking_start" => "2022/11/11 01:12",
            "booking_end" => "2022/11/11 08:12"
        ];

        $this->json('POST', '/booking/calculate-price', $data)
             ->seeJsonEquals([
                'created' => true,
             ]);
    }

    // test : booking/12/pay : PUT
    public function testUpdatePay()
    {
        $this->json('PUT', '/booking/12/pay')
             ->seeJsonEquals([
                'created' => true,
             ]);
    }

    // test : booking/check-availability : GET
    public function testGetCheckAvailability()
    {
        $this->json('GET', '/booking/check-availability')
             ->seeJsonEquals([
                'created' => true,
             ]);
    }
}