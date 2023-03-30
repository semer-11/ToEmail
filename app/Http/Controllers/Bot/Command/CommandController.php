<?php

namespace App\Http\Controllers\Bot\Command;

use App\Http\Controllers\Bot\Helpers\TelegramHelper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class CommandController
{
    use TelegramHelper;

    public static function handleCommand($command, $from)
    {
        if (Str::lower($command) == "/start") {
            return (new self)->start($from);
        } else if (Str::lower($command) == "/change") {
            return (new self)->change($from);
        }

        return (new self)->default($from);
    }

    public function start($from)
    {
        return $this->telegram->sendMessage(['chat_id' => $from->id, 'text' => __("start")]);
    }

    public function change($from)
    {
        Cache::forget($from->id);
        return $this->telegram->sendMessage(['chat_id' => $from->id, 'text' => __("change_email")]);
    }

    public function default($from)
    {
        return $this->telegram->sendMessage(['chat_id' => $from->id, 'text' => __("default"), 'parse_mode' => "HTML"]);
    }
}
