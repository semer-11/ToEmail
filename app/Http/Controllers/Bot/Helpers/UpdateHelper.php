<?php

namespace App\Http\Controllers\Bot\Helpers;


class UpdateHelper
{


    protected  $update;

    public function __construct($update)
    {
        $this->update = $update;
    }



    public  function isMessage()
    {
        return $this->update->message ?? false;
    }
    public  function isCallback()
    {
        return $this->update->callback_query ?? false;
    }
    public  function isChannelPost()
    {
        return $this->update->channel_post ?? false;
    }

    public  function from()
    {

        if ($this->isMessage()) {
            return $this->update->message->from;
        } else if ($this->isCallback()) {
            return $this->update->callback_query->from;
        } else if ($this->isChannelPost()) {
            return $this->update->channel_post->sender_chat;
        }
    }

    public  function id()
    {
        if ($this->isMessage()) {
            return $this->update->message->from->id;
        } else if ($this->isCallback()) {
            return $this->update->callback_query->from->id;
        } else if ($this->isChannelPost()) {
            return $this->update->channel_post->sender_chat->id;
        }
    }

    public  function message()
    {
        if ($this->isMessage()) {
            return $this->update->message->text;
        } else if ($this->isCallback()) {
            return $this->update->callback_query->data;
        }
    }
    public  function messageId()
    {
        if ($this->isMessage()) {
            return $this->update->message->message_id;
        } else if ($this->isCallback()) {
            return $this->update->callback_query->message->message_id;
        } else if ($this->isChannelPost()) {
            return $this->update->channel_post->message_id;
        }
    }

    public  function hasPhoto()
    {

        return $this->update->message->photo ? true : false;
    }
    public  function hasDocument()
    {

        return $this->update->message->document ? true : false;
    }
    public  function document()
    {
        return (object) ["file_id" => $this->update->message->document->file_id, "file_size" => $this->update->message->document->file_size, "mime_type" => $this->mimeType()];
    }

    public function mimeType()
    {
        if ($this->hasDocument()) {
            $meme = $this->update->message->document->mime_type;
            return explode("/", $meme)[1];
        }
        return "png";
    }

    public function photo()
    {

        return $this->update->message->photo[0] ?? null;
    }

    public  function hasFile()
    {
        return $this->hasPhoto() ? true : ($this->hasDocument() ? true : false);
    }
    public  function file()
    {
        if ($this->hasFile()) {

            return $this->photo() ?? $this->document();
        }
        return [];
    }

    public function caption()
    {
        return $this->update->message->caption;
    }
    public  function hasEntities()
    {
        return $this->update->message->entities ?? false;
    }
    public function isCommand()
    {
        if ($this->hasEntities()) {
            foreach ($this->hasEntities() as $ent) {
                if ($ent->type == "bot_command") {
                    return true;
                }
            }
        }
        return false;
    }
    public  function entities()
    {
        return $this->update->message->entities[0];
    }
}
