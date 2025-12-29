<?php
/**
 * Загрузчик компонентов MVC
 *
 * Обеспечивает загрузку контроллеров, моделей, представлений,
 * библиотек, хелперов, конфигураций и языковых файлов.
 */
final class Loader
{
    /**
     * Реестр зависимостей
     *
     * @var Registry
     */
    protected $registry;

    /**
     * Конструктор загрузчика
     *
     * @param Registry $registry Реестр зависимостей
     */
    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    /**
     * Загружает и выполняет контроллер
     *
     * @param string $route Маршрут контроллера (например, 'catalog/product')
     * @param array $data Данные для передачи в контроллер
     * @return mixed Результат выполнения контроллера или false при ошибке
     */
    public function controller($route, $data = [])
    {
        $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string) $route);

        $result = $this->registry->get('event')->trigger(
            'controller/' . $route . '/before',
            [&$route, &$data]
        );

        if ($result) {
            return $result;
        }

        $action = new Action($route);
        $output = $action->execute($this->registry, [&$data]);

        $result = $this->registry->get('event')->trigger(
            'controller/' . $route . '/after',
            [&$route, &$data, &$output]
        );

        if (!($output instanceof Exception)) {
            return $output;
        }

        return false;
    }

    /**
     * Загружает модель и регистрирует её в реестре
     *
     * @param string $route Маршрут модели (например, 'catalog/product')
     * @throws \Exception Если файл модели не найден
     * @return void
     */
    public function model($route)
    {
        $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string) $route);

        $file = DIR_APPLICATION . 'model/' . $route . '.php';
        $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $route);

        if (is_file($file)) {
            include_once($file);

            $proxy = new Proxy();

            foreach (get_class_methods($class) as $method) {
                $proxy->{$method} = $this->callback($this->registry, $route . '/' . $method);
            }

            $registryKey = 'model_' . str_replace(['/', '-', '.'], ['_', '', ''], (string) $route);
            $this->registry->set($registryKey, $proxy);
        } else {
            throw new \Exception('Error: Could not load model ' . $route . '!');
        }
    }

    /**
     * Загружает и рендерит представление
     *
     * Поддерживает автоматическое определение мобильной/планшетной версии шаблона.
     *
     * @param string $route Маршрут представления
     * @param array $data Данные для передачи в представление
     * @return string|mixed Отрендеренный HTML или результат события
     */
    public function view($route, $data = [])
    {
        $route = str_replace('../', '', (string) $route);

        $result = $this->registry->get('event')->trigger(
            'view/' . $route . '/before',
            [&$route, &$data]
        );

        $_SESSION['tmpl'] = 1;

        $route = $this->detectMobileTemplate($route);

        if ($result) {
            return $result;
        }

        $template = new Template('basic');

        foreach ($data as $key => $value) {
            $template->set($key, $value);
        }

        $output = $template->render($route . '.tpl');

        $result = $this->registry->get('event')->trigger(
            'view/' . $route . '/after',
            [&$route, &$data, &$output]
        );

        if ($result) {
            return $result;
        }

        return $output;
    }

    /**
     * Загружает библиотеку и регистрирует её в реестре
     *
     * @param string $route Маршрут библиотеки
     * @throws \Exception Если файл библиотеки не найден
     * @return void
     */
    public function library($route)
    {
        $route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string) $route);

        $file = DIR_SYSTEM . 'library/' . $route . '.php';
        $class = str_replace('/', '\\', $route);

        if (is_file($file)) {
            include_once($file);
            $this->registry->set(basename($route), new $class($this->registry));
        } else {
            throw new \Exception('Error: Could not load library ' . $route . '!');
        }
    }

    /**
     * Загружает хелпер
     *
     * @param string $route Маршрут хелпера
     * @throws \Exception Если файл хелпера не найден
     * @return void
     */
    public function helper($route)
    {
        $file = DIR_SYSTEM . 'helper/' . str_replace('../', '', (string) $route) . '.php';

        if (is_file($file)) {
            include_once($file);
        } else {
            throw new \Exception('Error: Could not load helper ' . $route . '!');
        }
    }

    /**
     * Загружает конфигурацию
     *
     * @param string $route Маршрут конфигурации
     * @return void
     */
    public function config($route)
    {
        $this->registry->get('event')->trigger('config/' . $route . '/before', $route);
        $this->registry->get('config')->load($route);
        $this->registry->get('event')->trigger('config/' . $route . '/after', $route);
    }

    /**
     * Загружает языковой файл
     *
     * @param string $route Маршрут языкового файла
     * @return array Массив языковых строк
     */
    public function language($route)
    {
        $this->registry->get('event')->trigger('language/' . $route . '/before', $route);

        $output = $this->registry->get('language')->load($route);

        $this->registry->get('event')->trigger('language/' . $route . '/after', $route);

        return $output;
    }

    /**
     * Создаёт callback для вызова метода модели
     *
     * @param Registry $registry Реестр зависимостей
     * @param string $route Маршрут метода модели
     * @return \Closure Callback функция
     */
    protected function callback($registry, $route)
    {
        return function ($args) use ($registry, &$route) {
            $result = $registry->get('event')->trigger(
                'model/' . $route . '/before',
                array_merge([&$route], $args)
            );

            if ($result) {
                return $result;
            }

            $routePath = substr($route, 0, strrpos($route, '/'));
            $file = DIR_APPLICATION . 'model/' . $routePath . '.php';
            $class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $routePath);
            $method = substr($route, strrpos($route, '/') + 1);

            if (is_file($file)) {
                include_once($file);
                $model = new $class($registry);
            } else {
                throw new \Exception('Error: Could not load model ' . $routePath . '!');
            }

            if (method_exists($model, $method)) {
                $output = call_user_func_array([$model, $method], $args);
            } else {
                throw new \Exception('Error: Could not call model/' . $route . '!');
            }

            $result = $registry->get('event')->trigger(
                'model/' . $route . '/after',
                array_merge([&$route, &$output], $args)
            );

            if ($result) {
                return $result;
            }

            return $output;
        };
    }

    /**
     * Определяет мобильную или планшетную версию шаблона
     *
     * @param string $route Исходный маршрут шаблона
     * @return string Модифицированный маршрут или исходный
     */
    private function detectMobileTemplate($route)
    {
        $detect = $this->registry->get('detect');
        $sessionTmpl = isset($_SESSION['tmpl']) ? $_SESSION['tmpl'] : 1;

        if ($detect->isMobile() || $sessionTmpl == 2) {
            $mobileTemplate = $this->buildAlternativeTemplatePath($route, '_mobile');
            if ($mobileTemplate) {
                return $mobileTemplate;
            }
        }

        if ($detect->isTablet() || $sessionTmpl == 3) {
            $tabletTemplate = $this->buildAlternativeTemplatePath($route, '_tablet');
            if ($tabletTemplate) {
                return $tabletTemplate;
            }
        }

        return $route;
    }

    /**
     * Строит путь к альтернативному шаблону
     *
     * @param string $route Исходный маршрут
     * @param string $suffix Суффикс для добавления (_mobile, _tablet)
     * @return string|null Путь к альтернативному шаблону или null
     */
    private function buildAlternativeTemplatePath($route, $suffix)
    {
        $firstPart = substr($route, 0, strpos($route, '/'));
        $alternativeTemplate = preg_replace(
            '/' . preg_quote($firstPart, '/') . '/',
            $firstPart . $suffix,
            $route
        );

        if (file_exists(DIR_TEMPLATE . $alternativeTemplate . '.tpl')) {
            return $alternativeTemplate;
        }

        return null;
    }
}
