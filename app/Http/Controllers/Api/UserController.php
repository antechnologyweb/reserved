<?php

namespace App\Http\Controllers\Api;

use App\Domain\Contracts\BookingContract;
use App\Domain\Contracts\UserContract;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Services\User\UserService;
use App\Services\Organization\OrganizationService;
use App\Services\Sms\SmsService;
use App\Services\Booking\BookingService;

use App\Http\Resources\UserResource;

use App\Jobs\BookingPayment;
use App\Jobs\UserPassword;
use App\Jobs\UserCode;

use App\Helpers\Time\Time;
use App\Helpers\Random\Random;

use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserGuestRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Requests\User\UserPasswordRequest;

class UserController extends Controller
{

    protected $userService;
    protected $smsService;
    protected $bookingService;
    protected $organizationService;

    public function __construct(UserService $userService, OrganizationService $organizationService, SmsService $smsService, BookingService $bookingService)
    {
        $this->userService  =   $userService;
        $this->smsService   =   $smsService;
        $this->bookingService   =   $bookingService;
        $this->organizationService  =   $organizationService;
    }

    public function guest(UserGuestRequest $userGuestRequest)
    {
        $data   =   $userGuestRequest->validated();
        $user   =   $this->userService->smsResend($data[UserContract::PHONE]);
        if (!$user) {
            $user   =   $this->userService->create($data);
            UserPassword::dispatch($user,$data[UserContract::PASSWORD]);
        }
        UserCode::dispatch($user);
        return $user;
    }

    public function booking(Request $request)
    {
        $user   =   $this->userService->getByPhone($request->input(UserContract::PHONE));

        if (!$user) {
            $password   =   Random::generate(8);
            $user   =   $this->userService->adminCreate([
                UserContract::USER_ID   =>  $request->input(UserContract::USER_ID),
                UserContract::NAME  =>  $request->input(UserContract::NAME),
                UserContract::PHONE =>  $request->input(UserContract::PHONE),
                UserContract::PHONE_VERIFIED_AT =>  date('Y-m-d H:i:s'),
                UserContract::PASSWORD  =>  $password
            ]);
            UserPassword::dispatch($user,$password);
        }

        $organization   =   $this->organizationService->getById($request->input(BookingContract::ORGANIZATION_ID));

        $booking    =   $this->bookingService->create([
            BookingContract::USER_ID    =>  $user->{UserContract::ID},
            BookingContract::ORGANIZATION_ID    =>  $request->input(BookingContract::ORGANIZATION_ID),
            BookingContract::ORGANIZATION_TABLE_LIST_ID  =>  $request->input(BookingContract::ORGANIZATION_TABLE_ID),
            BookingContract::TIME   =>  Time::toLocal($request->input(BookingContract::DATE).' '.$request->input(BookingContract::TIME), $request->input(BookingContract::TIMEZONE)),
            BookingContract::DATE   =>  $request->input(BookingContract::DATE),
            BookingContract::PRICE  =>  $organization->{BookingContract::PRICE}
        ]);

        BookingPayment::dispatch([
            BookingContract::ID =>  $booking->id,
            BookingContract::ORGANIZATION_ID    =>  $request->input(BookingContract::ORGANIZATION_ID),
            BookingContract::USER_ID    =>  $user->{UserContract::ID}
        ]);

        return $booking;
    }

    public function getByPhone($phone)
    {
        $user   =   $this->userService->getByPhone($phone);
        if ($user) {
            return new UserResource($user);
        }
        return response(['message'  =>  '???????????????????????? ???? ????????????'],404);
    }

    public function update($id, UserUpdateRequest $userUpdateRequest)
    {
        return new UserResource($this->userService->update($id,$userUpdateRequest->validated()));
    }

    public function updatePassword($id, UserPasswordRequest $userPasswordRequest)
    {
        $data   =   $userPasswordRequest->validated();
        $user   =   $this->userService->getById($id);
        if (Hash::check($data[UserContract::OLD], $user->{UserContract::PASSWORD})) {
            if (strlen($data[UserContract::NEW]) >= 8) {
                $this->userService->update($id,[
                    UserContract::PASSWORD  =>  Hash::make($data[UserContract::NEW])
                ]);
                return response(['message'  =>  '?????? ???????????? ?????????????? ??????????????'],200);
            }
            return response(['message'  =>  '???????????? ???????????? ?????????????????? ?????????????? ???? 8 ????????????????'],400);
        }
        return response(['message'  =>  '???? ???????????????????? ????????????'],400);
    }

    public function getById($id)
    {
        $user   =   $this->userService->getById($id);
        if ($user) {
            return new UserResource($user);
        }
        return response(['message'  =>  '???????????????????????? ???? ????????????'],404);
    }

    public function smsVerify($phone,$code)
    {
        $user   =   $this->userService->smsVerify($phone,$code);
        if ($user) {
            return new UserResource($user);
        }
        return response(['message'  =>  'incorrect code'],400);
    }

    public function smsResend($phone)
    {
        $user   =   $this->userService->smsResend($phone);
        if ($user) {
            $this->smsService->sendCode($user->phone,$user->code);
            return new UserResource($user);
        }
        return response(['message'  =>  'Phone doesn\'t exist'],400);
    }

    public function token($token)
    {
        $user   =   $this->userService->getByApiToken($token);
        if ($user) {
            return new UserResource($user);
        }
        return response(['message'  =>  'token expired'],404);
    }

    public function login(string $phone, string $password)
    {
        $user   =   $this->userService->getByPhoneAndPassword($phone);
        if ($user && Hash::check($password,$user->password)) {
            return new UserResource($user);
        }
        return response(['message'  =>  'incorrect phone or password'],401);
    }

    public function register(UserCreateRequest $request)
    {
        $user   =   $this->userService->create($request->validated());
        $this->smsService->sendCode($user->phone,$user->code);
        return new UserResource($user);
    }

}
