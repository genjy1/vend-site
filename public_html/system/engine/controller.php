<?php
/**
 * Базовый абстрактный класс контроллера
 *
 * Предоставляет доступ к реестру зависимостей через магические методы.
 * Все контроллеры приложения должны наследоваться от этого класса.
 */
abstract class Controller
{
    /**
     * Реестр зависимостей
     *
     * @var Registry
     */
    protected $registry;

    /**
     * Конструктор контроллера
     *
     * @param Registry $registry Реестр зависимостей
     */
    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    /**
     * Магический метод для получения зависимости из реестра
     *
     * @param string $key Ключ зависимости
     * @return mixed Зависимость из реестра
     */
    public function __get($key)
    {
        return $this->registry->get($key);
    }

    /**
     * Магический метод для установки зависимости в реестр
     *
     * @param string $key Ключ зависимости
     * @param mixed $value Значение
     * @return void
     */
    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }
}
