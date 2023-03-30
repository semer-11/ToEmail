<?php

namespace App\Http\Controllers\Bot;

use App\Http\Controllers\Bot\Command\CommandController;
use App\Http\Controllers\Bot\File\FileController;
use App\Http\Controllers\Bot\Generic\GenericController;
use App\Http\Controllers\Bot\Helpers\UpdateHelper;


class Update
{

    public static function handleUpdate($update)
    {
        $updateHelper = new UpdateHelper($update);
        if ($updateHelper->isCommand()) {
            return CommandController::handleCommand($updateHelper->message(), $updateHelper->from());
        } else if ($updateHelper->hasFile()) {
            return FileController::handleFile($updateHelper->file(), $updateHelper->from(), $updateHelper->caption());
        }
        return GenericController::handleGeneric($updateHelper->message(), $updateHelper->from());
    }
}
