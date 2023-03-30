<?php

namespace App\Http\Controllers\Bot\File;

use App\Http\Controllers\Bot\Helpers\TelegramHelper;
use App\Mail\EmailAttachment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class FileController
{
    use TelegramHelper;
    private const MAX_FILE_SIZE = 20971520;
    public static function handleFile($file, $sendTo, $caption = null)
    {

        /* 
            $file object
            {
                "file_id":"file_id",
                "file_size":123456789,
                "mime_type":"pdf"
            }
            $sendTo object
           {
                "id": telegram_id,
                "is_bot": false,
                "first_name": "Semer Nur",
                "username": "semer_nur",
                "language_code": "en"
            }
            */
        if (!Cache::has($sendTo->id)) {

            return (new self)->telegram->sendMessage(['chat_id' => $sendTo->id, 'text' => __("not_registered")]);
        }
        $msg = (new self)->telegram->sendMessage(['chat_id' => $sendTo->id, 'text' => __("downloading")]);
        try {

            //Bots can only download file not greater than 20MB
            if ($file->file_size > self::MAX_FILE_SIZE) {
                return (new self)->telegram->editMessageText(['chat_id' => $sendTo->id, 'text' => __("max_file_size"), 'message_id' => $msg->message_id]);
            }
            //downloadFile method doesn't ship with the package `telegram-bot-sdk` by //?irazasyed
            //you can get the modified version of this package at 
            //! https://github.com/semer-11/telegram-bot-sdk


            //** Downloading the file from telegram
            $file_path = (new self)->telegram->downloadFile($file);

            (new self)->telegram->editMessageText(['chat_id' => $sendTo->id, 'text' => __("downloaded"), 'message_id' => $msg->message_id]);
            Mail::to(Cache::get($sendTo->id))->send(new EmailAttachment($caption, $file_path));
            (new self)->telegram->editMessageText(['chat_id' => $sendTo->id, 'text' => __("sent", ['email' => Cache::get($sendTo->id)]), 'message_id' => $msg->message_id]);
            // Once we sent the file we don't need it anymore
            // የሰው ነገር ምን ያረግልናል በሚለው ;)
            Storage::delete($file_path);
        } catch (\Throwable $th) {
            (new self)->telegram->editMessageText(['chat_id' => $sendTo->id, 'text' => __("error"), 'message_id' => $msg->message_id]);
        }
    }
}
