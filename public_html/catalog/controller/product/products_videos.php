<?php


class ControllerProductVideos extends Controller {

    public function index()
    {

        $this->response->setHeader('Content-Type', 'application/json');
        $this->response->setOutput(json_encode(['data' => 'test']));

    }

}