<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 06/08/18
 * Time: 13:45
 */

namespace Louis\LaravelPushFCM;

use Illuminate\Support\Collection;
use Countable;
use LaravelFCM\Facades\FCM;
use Illuminate\Http\Response;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadData;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Response\DownstreamResponse;

class PushService
{

    const DEVICE_ANDROID = 'android';
    const DEVICE_IOS = 'ios';

    public function send($notifiable, Push $notification)
    {
        $this->enviar($notifiable, $notification->toPush($notifiable));
    }

    public static function sendToMany($notifiable, Push $notification)
    {
        $pushService = new PushService();
        $pushService->enviar($notifiable, $notification->toPush($notifiable));
    }

    /**
     * @param array $usuarios
     * @param PushResource $push
     */
    public function enviar($usuarios, PushResource $push)
    {
        $android = $this->getAndroidUsers($usuarios);
        $ios = $this->getIosUsers($usuarios);
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setContentAvailable(true);

        $notificationBuilder = new PayloadNotificationBuilder($push->getTitle());
        $notificationBuilder->setBody($push->getBody());

        $dataBuilder = new PayloadDataBuilder();

        if($clickAction = $push->getClickAction()){
            $notificationBuilder->setClickAction($clickAction);
        }

        $dataBuilder->addData($push->getData());

        if (!blank($android)) {
            $this->enviarParaAndroid($android, $optionBuilder, $dataBuilder);
        } else {
            \Log::channel('notifications')->error('Sem device token android para mandar');
        }

        if (!blank($ios)) {
            $this->enviarParaIos($ios, $optionBuilder, $notificationBuilder, $dataBuilder);
        } else {
            \Log::channel('notifications')->error('Sem device token ios para mandar');
        }
    }

    /**
     * @param $deviceToken
     * @param OptionsBuilder $optionBuilder
     * @param PayloadDataBuilder $dataBuilder
     */
    public function enviarParaAndroid($deviceToken, OptionsBuilder $optionBuilder, PayloadDataBuilder $dataBuilder)
    {
        try {
            /** @var PayloadData $data */
            $data = $dataBuilder->build();
            $option = $optionBuilder->build();

            /** @var DownstreamResponse $downstreamResponse */
            $downstreamResponse = FCM::sendTo($deviceToken, $option, null, $data);
            if ($downstreamResponse->numberFailure() > 0) {
                throw new \Exception('O envio falhou para ' . $downstreamResponse->numberFailure() . ' usuarios', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            \Log::channel('notifications')->info(
                'PushTrait notification enviado com sucesso para devices Android ' . PHP_EOL .
                'payload: ' . json_encode($data->toArray())
            );
        } catch (\Exception $e) {
            \Log::channel('notifications')->error(
                'Erro ao mandar push android para os device tokens: ' . PHP_EOL .
                'Exception: ' . $e->getMessage() . PHP_EOL .
                'Code: ' . $e->getCode()
            );
        }
    }

    public function enviarParaIos($deviceToken, OptionsBuilder $optionBuilder, PayloadNotificationBuilder $notificationBuilder, PayloadDataBuilder $dataBuilder)
    {
        try {
            $data = $dataBuilder->build();
            $option = $optionBuilder->build();
            $notification = $notificationBuilder->build();

            /** @var DownstreamResponse $downstreamResponse */
            $downstreamResponse = FCM::sendTo($deviceToken, $option, $notification, $data);
            if ($downstreamResponse->numberFailure() > 0) {
                throw new \Exception('O envio falhou para ' . $downstreamResponse->numberFailure() . ' usuarios', Response::HTTP_INTERNAL_SERVER_ERROR);

            }
            \Log::channel('notifications')->info(
                'PushTrait notification enviado com sucesso para devices IOS ' . PHP_EOL .
                'Device tokens: ' . is_array($deviceToken) ? json_encode($deviceToken) : $deviceToken
            );
        } catch (\Exception $e) {
            \Log::channel('notifications')->error(
                'Erro ao mandar push ios para os device tokens: ' . is_array($deviceToken) ? json_encode($deviceToken) : $deviceToken . PHP_EOL .
                    'Exception: ' . $e->getMessage() . PHP_EOL .
                    'Code: ' . $e->getCode()
            );
        }
    }

    /**
     * @param array|Collection|UserWithMobile $usuarios
     * @return array|string|null
     */
    public function getAndroidUsers($usuarios)
    {
        if ($usuarios instanceof UserWithMobile) {
            return $usuarios->getDeviceCollection()->getDeviceTokensAndroid();
        }

        $android = [];
        foreach ($usuarios as $usuario) {
            $deviceToken = $this->getandroidUsers($usuario);

            if (is_array($deviceToken)) {
                $android = array_merge($android, $deviceToken);
                continue;
            }

            $android[] = $deviceToken;
        }
        return $android;
    }

    /**
     * @param array|Collection|UserWithMobile $usuarios
     * @return array
     */
    public function getIosUsers($usuarios)
    {
        if ($usuarios instanceof UserWithMobile) {
            return $usuarios->getDeviceCollection()->getDeviceTokensIos();
        }

        $ios = [];
        foreach ($usuarios as $usuario) {
            $deviceToken = $this->getIosUsers($usuario);

            if (is_array($deviceToken)) {
                $ios = array_merge($ios, $deviceToken);
                continue;
            }

            $ios[] = $deviceToken;
        }

        return $ios;
    }
}
