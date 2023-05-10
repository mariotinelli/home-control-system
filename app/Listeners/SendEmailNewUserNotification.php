<?php

namespace App\Listeners;

use App\Events\CreatedNewUser;

class SendEmailNewUserNotification
{
    public function handle(CreatedNewUser $event): void
    {
        $event->user->notify(new \App\Notifications\NewUserNotification());
    }

}
