<?php
class ControllerInformationVideos extends Controller {
    public function index() {
        try {
            // Загружаем модель
            $this->load->model('information/videos');

            // Получаем данные из модели
            $data = $this->model_information_videos->getVideos();

            // Правильная установка заголовка
            $this->response->addHeader('Content-Type: application/json; charset=utf-8');

            // Корректный вызов json_encode
            $this->response->setOutput(json_encode(['data' => $data], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        } catch (Exception $e) {
            // Ловим ошибку и возвращаем её в JSON
            $this->response->addHeader('Content-Type: application/json; charset=utf-8');
            $this->response->setOutput(json_encode(['error' => $e->getMessage()]));
        }
    }
}
