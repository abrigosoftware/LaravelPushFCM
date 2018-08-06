<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 06/08/18
 * Time: 14:04
 */

namespace App\PushNotification;


interface Push
{
    public function toPush() : PushResource;
}