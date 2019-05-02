<?php
/**
 * Created by PhpStorm.
 * User: devmaker
 * Date: 06/08/18
 * Time: 14:10
 */

namespace Louis\LaravelPushFCM;


class PushResource
{
    /**
     * @var String
     */
    private $title;
    /**
     * @var String
     */
    private $body;

    /**
     * @var array
     */
    private $data;

    /**
     * @var String
     */
    private $clickAction;

    /**
     * PushResource constructor.
     * @param String $title
     * @param String $body
     * @param array $data
     */
    public function __construct(String $title, String $body, array $data = [])
    {

        $this->title = $title;
        $this->body = $body;
        $data['title'] = $title;
        $data['body'] = $body;
        $this->data = $data;
    }

    public function setClickAction(String $clickAction)
    {
        $this->clickAction = $clickAction;
    }

    /**
     * @return String
     */
    public function getClickAction()
    {
        return $this->clickAction;
    }

    /**
     * @return String
     */
    public function getBody(): String
    {
        return $this->body;
    }

    /**
     * @return String
     */
    public function getTitle(): String
    {
        return $this->title;
    }

    /**
     * @param array $data
     * @return PushResource
     */
    public function setData(array $data): PushResource
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


}