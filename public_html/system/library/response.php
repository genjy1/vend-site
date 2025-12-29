<?php
/**
 * Класс для управления HTTP ответом
 *
 * Обрабатывает заголовки, сжатие и вывод контента.
 */
class Response
{
    /**
     * Массив HTTP заголовков
     *
     * @var array
     */
    private $headers = [];

    /**
     * Уровень сжатия (0-9)
     *
     * @var int
     */
    private $level = 0;

    /**
     * Содержимое ответа
     *
     * @var string
     */
    private $output;

    /**
     * Добавляет HTTP заголовок
     *
     * @param string $header Заголовок в формате "Name: Value"
     * @return void
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;
    }

    /**
     * Выполняет HTTP редирект
     *
     * @param string $url URL для редиректа
     * @param int $status HTTP код статуса (по умолчанию 302)
     * @return void
     */
    public function redirect($url, $status = 302)
    {
        $cleanUrl = str_replace(['&amp;', "\n", "\r"], ['&', '', ''], $url);
        header('Location: ' . $cleanUrl, true, $status);
        exit();
    }

    /**
     * Устанавливает уровень сжатия gzip
     *
     * @param int $level Уровень сжатия (0-9)
     * @return void
     */
    public function setCompression($level)
    {
        $this->level = $level;
    }

    /**
     * Возвращает текущее содержимое ответа
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Устанавливает содержимое ответа
     *
     * @param string $output Содержимое
     * @return void
     */
    public function setOutput($output)
    {
        $this->output = $output;
    }

    /**
     * Сжимает данные с помощью gzip
     *
     * @param string $data Данные для сжатия
     * @param int $level Уровень сжатия
     * @return string Сжатые данные или оригинальные при ошибке
     */
    private function compress($data, $level = 0)
    {
        $encoding = null;

        if (isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
            if (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false) {
                $encoding = 'gzip';
            } elseif (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'x-gzip') !== false) {
                $encoding = 'x-gzip';
            }
        }

        if (!$encoding || $level < -1 || $level > 9) {
            return $data;
        }

        if (!extension_loaded('zlib') || ini_get('zlib.output_compression')) {
            return $data;
        }

        if (headers_sent() || connection_status()) {
            return $data;
        }

        $this->addHeader('Content-Encoding: ' . $encoding);

        return gzencode($data, (int) $level);
    }

    /**
     * Выводит содержимое ответа
     *
     * Обрабатывает сжатие и отправляет заголовки если они ещё не отправлены.
     *
     * @return void
     */
    public function output()
    {
        if (!defined('HTTP_CATALOG')) {
            $this->output = str_replace('index.php?route=common/home', '', $this->output);
        }

        if ($this->output) {
            $output = $this->level
                ? $this->compress($this->output, $this->level)
                : $this->output;

            if (!headers_sent()) {
                foreach ($this->headers as $header) {
                    header($header, true);
                }
            }

            echo $output;
        }
    }
}
