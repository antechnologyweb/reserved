<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Services\Payment\PaymentService;
use App\Services\Booking\BookingService;

class PaymentController extends Controller
{
    protected $paymentService;
    protected $bookingService;
    public function __construct(PaymentService $paymentService, BookingService $bookingService) {
        $this->paymentService   =   $paymentService;
        $this->bookingService   =   $bookingService;
    }

    public function cardAdd($id) {
        return $this->paymentService->cardAdd($id);
    }

    public function cardList($id) {
        return $this->paymentService->cardList($id);
    }

    public function result(Request $request):void {
        $this->paymentService->result($request->all());
        $this->bookingService->result($request->all());
    }

    public function post(Request $request) {
        return $this->paymentService->post($request->all());
    }

    public function check(Request $request) {
        return $this->paymentService->check($request->all());
    }

    public function success(Request $request) {
        return $this->paymentService->success($request->all());
    }

    public function failure(Request $request) {
        return $this->paymentService->failure($request->all());
    }
}
