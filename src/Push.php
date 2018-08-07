<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 06/08/18
 * Time: 14:04
 */

namespace Louis\LaravelPushFCM;


interface Push
{
    public function toPush($notifiable) : PushResource;
}