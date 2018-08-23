<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 07/08/18
 * Time: 15:36
 */

namespace Louis\LaravelPushFCM;


class DeviceCollection
{
    private $collection;
    private $androidTokens = [];
    private $iosTokens = [];

    public function __construct(array $itens)
    {
        $this->collection = collect($itens);
    }

    public function get()
    {
        return $this->collection->all();
    }

    public function loadDeviceTokens()
    {
        /**
         * @var DeviceResource $item
         */
        foreach ($this->collection as $item) {
            if ($item->isAndroid()) {
                $this->androidTokens[] = $item->getToken();
            }
            if ($item->isIos()) {
                $this->iosTokens[] = $item->getToken();
            }
        }
    }

    public function getDeviceTokensIos()
    {
        $this->loadDeviceTokensIfNotLoaded();
        return $this->iosTokens;
    }

    public function getDeviceTokensAndroid()
    {
        $this->loadDeviceTokensIfNotLoaded();
        return $this->androidTokens;
    }

    public function loadDeviceTokensIfNotLoaded()
    {
        if (count($this->androidTokens) <= 0 && count($this->iosTokens) <= 0) {
            $this->loadDeviceTokens();
        }
    }

}
