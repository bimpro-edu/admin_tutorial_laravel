<?php

namespace App\Utils;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;
use ThaoHR\User;

class FcmUtils {
    public static function sendFcmMessage($tokens, $title, $message, $dataParams) {
        if (count($tokens)) {
            if (!isset($dataParams['title'])) {
                $dataParams['title'] = $title;
            }
            if (!isset($dataParams['message'])) {
                $dataParams['message'] = $message;
            }

            try {
                self::sendFcmMessageToFirebase($tokens, $title, $message, $dataParams);
            } catch (Exception $e) {
                
            }
        }
    }

    private static function sendFcmMessageToFirebase($tokens, $title, $message, $dataParams) {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($message)->setSound('default');
        $notificationBuilder->setClickAction('FCM_PLUGIN_ACTIVITY');
        $notificationBuilder->setBadge($dataParams['badge']);
        $notificationBuilder->setTag($dataParams['tag']);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($dataParams);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);

        $deleteTokens = $downstreamResponse->tokensToDelete();
        if (count($deleteTokens)) {
            User::whereIn('fcm_token', $deleteTokens)->update(['fcm_token' => null]);
        }

        $modifyTokens = $downstreamResponse->tokensToModify();
        if (count($modifyTokens)) {
            foreach ($modifyTokens as $oldToken => $newToken) {
                $user = User::where('fcm_token', $oldToken)->first();
                if (isset($deviceTokenTemp)) {
                    $user->update(['fcm_token' => $newToken]);
                }
            }
        }

        $retryTokens = $downstreamResponse->tokensToRetry();
        if (count($retryTokens)) {
            foreach ($retryTokens as $retryToken) {
                FCM::sendTo($retryToken, $option, $notification, $data);
            }
        }

        $errorTokens = $downstreamResponse->tokensWithError();
        if (count($errorTokens)) {
            User::whereIn('fcm_token', $deleteTokens)->update(['fcm_token' => null]);
        }
    }

}