<?php
/**
 * Класс для логирования сообщений в файл
 *
 * Простой логгер для записи сообщений в текстовые файлы.
 * Поддерживает запись любых типов данных через print_r.
 */
class Log
{
    /**
     * Файловый дескриптор для записи
     *
     * @var resource|false
     */
    private $handle;

    /**
     * Путь к файлу лога
     *
     * @var string
     */
    private $filename;

    /**
     * Конструктор
     *
     * @param string $filename Имя файла лога (без пути)
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        $this->handle = fopen(DIR_LOGS . $filename, 'a');
    }

    /**
     * Записывает сообщение в лог файл
     *
     * @param mixed $message Сообщение для записи (строка, массив или объект)
     * @return void
     */
    public function write($message)
    {
        if ($this->handle) {
            $timestamp = date('Y-m-d G:i:s');
            $formattedMessage = print_r($message, true);
            fwrite($this->handle, $timestamp . ' - ' . $formattedMessage . "\n");
        }
    }

    /**
     * Записывает сообщение с уровнем INFO
     *
     * @param string $message Сообщение
     * @param array $context Дополнительный контекст
     * @return void
     */
    public function info($message, array $context = [])
    {
        $this->log('INFO', $message, $context);
    }

    /**
     * Записывает сообщение с уровнем WARNING
     *
     * @param string $message Сообщение
     * @param array $context Дополнительный контекст
     * @return void
     */
    public function warning($message, array $context = [])
    {
        $this->log('WARNING', $message, $context);
    }

    /**
     * Записывает сообщение с уровнем ERROR
     *
     * @param string $message Сообщение
     * @param array $context Дополнительный контекст
     * @return void
     */
    public function error($message, array $context = [])
    {
        $this->log('ERROR', $message, $context);
    }

    /**
     * Записывает сообщение с уровнем DEBUG
     *
     * @param string $message Сообщение
     * @param array $context Дополнительный контекст
     * @return void
     */
    public function debug($message, array $context = [])
    {
        $this->log('DEBUG', $message, $context);
    }

    /**
     * Внутренний метод для записи лога с уровнем
     *
     * @param string $level Уровень логирования
     * @param string $message Сообщение
     * @param array $context Дополнительный контекст
     * @return void
     */
    private function log($level, $message, array $context = [])
    {
        if ($this->handle) {
            $timestamp = date('Y-m-d H:i:s');
            $contextString = !empty($context) ? ' ' . json_encode($context, JSON_UNESCAPED_UNICODE) : '';
            fwrite($this->handle, "[{$timestamp}] [{$level}] {$message}{$contextString}\n");
        }
    }

    /**
     * Деструктор - закрывает файловый дескриптор
     */
    public function __destruct()
    {
        if ($this->handle) {
            fclose($this->handle);
        }
    }
}
