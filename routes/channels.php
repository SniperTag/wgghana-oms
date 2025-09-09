<?php

use Illuminate\Support\Facades\Broadcast;

/*
|------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
| Here you may register all of the event broadcasting channels that your
| application supports. The given channels are used to authenticate
| access to your event broadcasting channels. The channel middleware
| ensures that only authenticated users can listen to these channels.
|------------------------------------------------------------------------
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
