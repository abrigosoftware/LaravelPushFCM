<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 07/08/18
 * Time: 15:33
 */

namespace Louis\LaravelPushFCM;


use Illuminate\Http\Response;

class DeviceResource
{
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $model;

     public function __construct(string $token = null, string $model = null)
     {
         $this->token = $token;
         $this->model = $model;

         if($model && in_array($model, [PushService::DEVICE_ANDROID, PushService::DEVICE_IOS]) === false){
             throw new \Exception('Modelo inválido', Response::HTTP_BAD_REQUEST);
         }
     }

    public function getToken()
    {
        return $this->token;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function isAndroid()
    {
        return $this->model == PushService::DEVICE_ANDROID;
    }

    public function isIos()
    {
        return $this->model == PushService::DEVICE_IOS;
    }
}