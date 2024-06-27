<?php
require_once(DIR_SYSTEM . 'library/unisender/UnisenderApi.php'); //подключаем файл класса


class Unisender
{
    private $apikey="6gqj5kisttpc5q37msrjwwr6crz6c6n4nxm5xu3o"; //API-ключ к вашему кабинету

    private $uni;

    private $listId = "3";

    function __construct()
    {
        $this->uni = new \Unisender\ApiWrapper\UnisenderApi($this->apikey);
    }

    public function subscribe($email) {
        $result = $this->uni->subscribe(["list_ids" => $this->listId, "fields" => ["email" => $email], "double_optin" => 3]);

        error_log(serialize($result));
    }

}