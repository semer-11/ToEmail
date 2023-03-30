<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Bot\Update;
use Telegram\Bot\Laravel\Facades\Telegram;

class BotController extends Controller
{
    //

    public function entry($token)
    {
        if ($token == config('app.BOT_TOKEN')) {
            //this condition help us to be sure
            // the request is really coming from Telegram server
            $update = Telegram::getWebhookUpdates();
            return Update::handleUpdate($update);
        }

        return response()->json([
            'status' => 403,
            'description' => 'Unknown source'
        ], 403);
    }
}
