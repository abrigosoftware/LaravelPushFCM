<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 06/08/18
 * Time: 14:07
 */

namespace Louis\LaravelPushFCM;


interface UserWithMobile
{
    public function getDeviceCollection() :  DeviceCollection;

}