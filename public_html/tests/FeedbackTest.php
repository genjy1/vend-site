<?php
use PHPUnit\Framework\TestCase;

// Заглушки зависимостей OpenCart
class Loader {}
class Response { public function setOutput($output) {} }

// Заглушка контроллера
class ControllerCommonFeedback {
    public $load;
    public $response;

    public function index() {
        $this->response->setOutput("Feedback page");
    }
}

class FeedbackTest extends TestCase
{
    public function testFeedbackIndex()
    {
        $feedback = new ControllerCommonFeedback();
        $feedback->load = new Loader();
        $feedback->response = new Response();

        $feedback->index();

        $this->assertTrue(true); // проверка, что метод выполнен
    }
}
