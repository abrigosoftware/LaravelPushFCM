<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 06/08/18
 * Time: 14:07
 */

namespace App\PushNotification;


interface UserWithMobile
{
    public function getDeviceModel() : string;

    public function getDeviceToken() : string;

}