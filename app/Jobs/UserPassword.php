<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\User;

use App\Helpers\Sms\Sms;

class UserPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $user;
    protected $password;

    public function __construct(User $user, string $password)
    {
        $this->user =   $user;
        $this->password =   $password;
    }

    public function handle(Sms $sms)
    {
        $sms->password($this->user,$this->password);
    }
}
