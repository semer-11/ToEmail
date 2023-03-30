<?php

namespace App\Http\Controllers\Bot\Helpers;

use Telegram\Bot\Api;



trait TelegramHelper
{

    public $telegram;

    public function __construct()
    {
        $this->telegram = new Api(config("app.BOT_TOKEN"));
    }
}
