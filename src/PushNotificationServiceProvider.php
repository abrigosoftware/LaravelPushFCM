<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 06/08/18
 * Time: 14:42
 */

namespace App\PushNotification;


use App\Channels\PushService;
use Illuminate\Notifications\ChannelManager;
use Illuminate\Support\ServiceProvider;

class PushNotificationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->make(ChannelManager::class)->extend('push', function () {
            return new PushService();
        });
    }
}