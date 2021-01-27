<?php

namespace ThaoHR\Support\Enum;

use ThaoHR\Client;

class ClientStatus
{
    public static function lists()
    {
        return [
            Client::STATUS_ACTIVE => trans('app.client_status.'.Client::STATUS_ACTIVE),
            Client::STATUS_INAVTIVE => trans('app.client_status.'. Client::STATUS_INAVTIVE),
        ];
    }
}
