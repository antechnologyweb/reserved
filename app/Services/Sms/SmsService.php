<?php


namespace App\Services\Sms;

use App\Services\BaseService;

use App\Domain\Repositories\Link\LinkRepositoryInterface;

use App\Helpers\Curl\Curl;

class SmsService extends BaseService
{
    protected $link     =   'https://reserved-app.com';
    protected $login    =   'An-technology';
    protected $password =   'ygABGazD55XJ4NcesmBo';
    protected $url      =   'https://smsc.kz/sys/send.php';
    protected $code;
    protected $userPassword;
    protected $userPhone;
    protected $curl;
    protected $linkRepository;

    public function __construct(Curl $curl, LinkRepositoryInterface $linkRepository) {
        $this->curl             =   $curl;
        $this->linkRepository   =   $linkRepository;
    }

    public function sendAuth(string $phone, string $password)
    {
        return $this->curl->get($this->url.'?'.$this->getAuthParameters($phone,$password));
    }

    public function sendBooking(string $phone, string $detail, string $link) {
        return $this->curl->get($this->url.'?'.$this->getPaymentParameters($phone,$detail,$link));
    }

    public function sendCode(string $phone, int $code) {
        $this->code =   $code;
        return $this->curl->get($this->url.'?'.$this->getParameters($phone));
    }

    public function sendUser(string $phone, string $password) {
        $this->userPhone    =   $phone;
        $this->userPassword =   $password;
        return $this->curl->get($this->url.'?'.$this->getUserParameters($phone));
    }

    public function getAuthParameters($phone,$password)
    {
        return http_build_query([
            'login'     =>  $this->login,
            'psw'       =>  $this->password,
            'phones'    =>  $phone,
            'mes'       =>  $this->messageAuth($password)
        ]);
    }

    public function getPaymentParameters($phone,$detail,$link) {
        return http_build_query([
            'login'     =>  $this->login,
            'psw'       =>  $this->password,
            'phones'    =>  $phone,
            'mes'       =>  $this->messagePayment($detail,$link)
        ]);
    }

    public function getUserParameters(string $phone):string
    {
        return http_build_query([
            'login'     =>  $this->login,
            'psw'       =>  $this->password,
            'phones'    =>  $phone,
            'mes'       =>  $this->messageUser()
        ]);
    }

    public function getParameters(string $phone):string {
        return http_build_query([
            'login'     =>  $this->login,
            'psw'       =>  $this->password,
            'phones'    =>  $phone,
            'mes'       =>  $this->message()
        ]);
    }

    public function messageAuth($password)
    {
        return $_SERVER['SERVER_NAME'].' ?????? ????????????: '.$password;
    }

    public function messagePayment($detail,$link):string {
        $url    =   $this->linkRepository->create($link);
        return '???????????????? ???????????????????????? '.$this->link.'/lk/'.$url->id;
    }

    public function messageUser():string {
        return '?????????? ???????????????????? ?? reserved.kz\n ?????? ??????????: '.$this->userPhone.'\n ?????? ????????????: '.$this->userPassword;
    }

    public function message():string {
        return '?????? ??????: '.$this->code.' ?????? ?????????????????????????? ??????????????????????';
    }

}
