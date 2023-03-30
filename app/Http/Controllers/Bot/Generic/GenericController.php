<?php

namespace App\Http\Controllers\Bot\Generic;

use App\Http\Controllers\Bot\Helpers\TelegramHelper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class GenericController
{

    use TelegramHelper;
    public static function handleGeneric($message, $from)
    {
        if (!Cache::has($from->id)) {
            return (new self)->register($message, $from);
        } else if (!is_string($message)) {
            return (new self)->telegram->sendMessage(['chat_id' => $from->id, 'text' => __("not_file")]);
        }
        //if we have cache with the specified key
        //we take it as the user has already registered email
        //so we tell them they are registered
        return (new self)->telegram->sendMessage(['chat_id' => $from->id, 'text' => __("already_registered")]);
    }

    public function register($email, $from)
    {
        $isValidEmail = Validator::make(['email' => $email], [
            'email' => 'required|email'
        ]);

        if (!$isValidEmail->fails()) {
            Cache::put($from->id, $email);
            return $this->telegram->sendMessage(['chat_id' => $from->id, 'text' => __("successfully_registered")]);
        }
        return $this->telegram->sendMessage(['chat_id' => $from->id, 'text' => __("insert_valid_email")]);
    }
}
