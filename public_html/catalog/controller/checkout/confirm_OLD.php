<?php
/**
 * Controller Checkout Confirm
 * Контроллер подтверждения и создания заказа с расширенной обработкой ошибок
 */
class ControllerCheckoutConfirm extends Controller {

    private const LOG_FILE = 'checkout.log';
    private const ERROR_LOG_FILE = 'checkout_errors.log';
    private const MIN_NAME_LENGTH = 2;
    private const MAX_NAME_LENGTH = 100;
    private const MIN_PHONE_LENGTH = 10;
    private const MAX_CART_TOTAL = 10000000; // Максимальная сумма заказа

    /**
     * Основной метод обработки подтверждения заказа
     */
    public function index() {
        $startTime = microtime(true);
        $requestId = $this->generateRequestId();

        // Устанавливаем обработчик ошибок
        $this->setupErrorHandlers($requestId);

        try {
            $this->logInfo('Checkout confirmation started', [
                'request_id' => $requestId,
                'customer_id' => $this->customer->isLogged() ? $this->customer->getId() : 0,
                'ip' => $this->getClientIp(),
                'user_agent' => $this->getUserAgent(),
                'session_id' => session_id()
            ]);

            // Инициализация всех необходимых компонентов
            $this->initializeComponents();

            // Инициализация моделей
            $this->initializeModels();

            // Валидация корзины
            $this->validateCart();

            // Валидация входных данных
            $validatedData = $this->validateInputData();

            // Подготовка данных заказа
            $orderData = $this->prepareOrderData($validatedData);

            // Валидация данных заказа перед созданием
            $this->validateOrderData($orderData);

            // Создание заказа (с транзакцией если доступно)
            $orderId = $this->createOrder($orderData);

            // Очистка корзины с обработкой ошибок
            $this->clearCart();

            // Дополнительные действия после создания заказа
            $this->afterOrderCreated($orderId, $orderData);

            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logInfo('Order created successfully', [
                'request_id' => $requestId,
                'order_id' => $orderId,
                'duration_ms' => $duration,
                'total' => $orderData['total']
            ]);

            // Формирование успешного ответа
            $json = [
                'success' => true,
                'order_id' => $orderId,
                'redirect' => $this->url->link('checkout/success'),
                'request_id' => $requestId
            ];

            return $this->sendJsonResponse($json);

        } catch (ValidationException $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logWarning('Validation error during checkout', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'field' => $e->getField(),
                'duration_ms' => $duration,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return $this->sendJsonResponse([
                'error' => true,
                'message' => $e->getMessage(),
                'field' => $e->getField(),
                'request_id' => $requestId
            ], 400);

        } catch (CartException $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logWarning('Cart error during checkout', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'duration_ms' => $duration
            ]);

            return $this->sendJsonResponse([
                'error' => true,
                'message' => $e->getMessage(),
                'request_id' => $requestId
            ], 400);

        } catch (DatabaseException $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Database error during checkout', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'query' => $e->getQuery(),
                'duration_ms' => $duration,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $this->formatTrace($e->getTrace())
            ]);

            return $this->sendJsonResponse([
                'error' => true,
                'message' => 'Ошибка базы данных. Пожалуйста, попробуйте позже.',
                'request_id' => $requestId
            ], 500);

        } catch (Exception $e) {
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('Unexpected error during checkout', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'type' => get_class($e),
                'duration_ms' => $duration,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $this->formatTrace($e->getTrace())
            ]);

            // Отправляем уведомление администратору о критической ошибке
            $this->notifyAdminAboutError($e, $requestId);

            return $this->sendJsonResponse([
                'error' => true,
                'message' => 'Произошла непредвиденная ошибка. Пожалуйста, попробуйте еще раз или свяжитесь с нами.',
                'request_id' => $requestId
            ], 500);

        } catch (Throwable $e) {
            // PHP 7+ для отлова фатальных ошибок
            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logError('FATAL error during checkout', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'type' => get_class($e),
                'duration_ms' => $duration,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $this->formatTrace($e->getTrace())
            ]);

            $this->notifyAdminAboutError($e, $requestId);

            return $this->sendJsonResponse([
                'error' => true,
                'message' => 'Произошла критическая ошибка. Наши специалисты уже работают над решением проблемы.',
                'request_id' => $requestId
            ], 500);
        }
    }

    /**
     * Настройка обработчиков ошибок
     *
     * @param string $requestId
     */
    private function setupErrorHandlers($requestId) {
        // Обработчик ошибок PHP
        set_error_handler(function($errno, $errstr, $errfile, $errline) use ($requestId) {
            // Не обрабатываем подавленные ошибки (@)
            if (!(error_reporting() & $errno)) {
                return false;
            }

            $errorTypes = [
                E_ERROR => 'ERROR',
                E_WARNING => 'WARNING',
                E_PARSE => 'PARSE',
                E_NOTICE => 'NOTICE',
                E_CORE_ERROR => 'CORE_ERROR',
                E_CORE_WARNING => 'CORE_WARNING',
                E_COMPILE_ERROR => 'COMPILE_ERROR',
                E_COMPILE_WARNING => 'COMPILE_WARNING',
                E_USER_ERROR => 'USER_ERROR',
                E_USER_WARNING => 'USER_WARNING',
                E_USER_NOTICE => 'USER_NOTICE',
                E_STRICT => 'STRICT',
                E_RECOVERABLE_ERROR => 'RECOVERABLE_ERROR',
                E_DEPRECATED => 'DEPRECATED',
                E_USER_DEPRECATED => 'USER_DEPRECATED',
            ];

            $errorType = $errorTypes[$errno] ?? 'UNKNOWN';

            $this->logError('PHP Error caught', [
                'request_id' => $requestId,
                'type' => $errorType,
                'errno' => $errno,
                'message' => $errstr,
                'file' => $errfile,
                'line' => $errline
            ]);

            // Для критических ошибок выбрасываем исключение
            if (in_array($errno, [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR])) {
                throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
            }

            return true;
        });

        // Обработчик исключений
        set_exception_handler(function($exception) use ($requestId) {
            $this->logError('Uncaught exception', [
                'request_id' => $requestId,
                'type' => get_class($exception),
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $this->formatTrace($exception->getTrace())
            ]);
        });
    }

    /**
     * Генерация уникального ID запроса
     *
     * @return string
     */
    private function generateRequestId() {
        return sprintf(
            '%s-%s-%s',
            date('YmdHis'),
            substr(md5(uniqid('', true)), 0, 8),
            substr(md5($this->getClientIp()), 0, 4)
        );
    }

    /**
     * Инициализация всех необходимых компонентов (используем магические методы Controller)
     *
     * @throws Exception
     */
    private function initializeComponents() {
        try {
            $this->logDebug('Starting components initialization');
            
            // В OpenCart 2 все объекты доступны через магический метод __get() в Controller
            // Не используем isset(), так как это не сработает с магическими свойствами
            
            // Проверяем критичные компоненты через try-catch
            $missingComponents = [];
            
            // Проверка request
            try {
                $test = $this->request;
                if (!is_object($test)) {
                    $missingComponents[] = 'request (not an object)';
                }
            } catch (Exception $e) {
                $missingComponents[] = 'request (exception: ' . $e->getMessage() . ')';
            }
            
            // Проверка config
            try {
                $test = $this->config;
                if (!is_object($test)) {
                    $missingComponents[] = 'config (not an object)';
                }
            } catch (Exception $e) {
                $missingComponents[] = 'config (exception: ' . $e->getMessage() . ')';
            }
            
            // Проверка db
            try {
                $test = $this->db;
                if (!is_object($test)) {
                    $missingComponents[] = 'db (not an object)';
                }
            } catch (Exception $e) {
                $missingComponents[] = 'db (exception: ' . $e->getMessage() . ')';
            }
            
            // Проверка url
            try {
                $test = $this->url;
                if (!is_object($test)) {
                    $missingComponents[] = 'url (not an object)';
                }
            } catch (Exception $e) {
                $missingComponents[] = 'url (exception: ' . $e->getMessage() . ')';
            }
            
            // Проверка session (не критична)
            try {
                $test = $this->session;
                if (!is_object($test)) {
                    $this->logWarning('Session not available or not an object');
                }
            } catch (Exception $e) {
                $this->logWarning('Session not available', ['error' => $e->getMessage()]);
            }
            
            // Проверка customer (не критична)
            try {
                $test = $this->customer;
                if (!is_object($test)) {
                    $this->logWarning('Customer not available or not an object');
                }
            } catch (Exception $e) {
                $this->logWarning('Customer not available', ['error' => $e->getMessage()]);
            }
            
            // Проверка response (не критична)
            try {
                $test = $this->response;
                if (!is_object($test)) {
                    $this->logWarning('Response not available or not an object');
                }
            } catch (Exception $e) {
                $this->logWarning('Response not available', ['error' => $e->getMessage()]);
            }
            
            // Проверка cart (критична)
            try {
                $test = $this->cart;
                if (!is_object($test)) {
                    $this->logWarning('Cart not available, attempting to create');
                    
                    // Пытаемся создать cart
                    try {
                        if (file_exists(DIR_SYSTEM . 'library/cart/cart.php')) {
                            require_once(DIR_SYSTEM . 'library/cart/cart.php');
                            $this->cart = new Cart\Cart($this->registry);
                            $this->logDebug('Cart created (Cart\Cart)');
                        } else if (file_exists(DIR_SYSTEM . 'library/cart.php')) {
                            require_once(DIR_SYSTEM . 'library/cart.php');
                            $this->cart = new Cart($this->registry);
                            $this->logDebug('Cart created (Cart)');
                        } else {
                            $missingComponents[] = 'cart (library file not found)';
                        }
                    } catch (Exception $e) {
                        $this->logError('Failed to create cart', ['error' => $e->getMessage()]);
                        $missingComponents[] = 'cart (failed to create: ' . $e->getMessage() . ')';
                    }
                } else {
                    $this->logDebug('Cart available');
                }
            } catch (Exception $e) {
                $this->logWarning('Cart not available', ['error' => $e->getMessage()]);
                $missingComponents[] = 'cart (exception: ' . $e->getMessage() . ')';
            }
            
            // Если есть критичные недостающие компоненты - выбрасываем исключение
            if (!empty($missingComponents)) {
                $this->logError('Some components missing', [
                    'missing' => $missingComponents
                ]);
                
                // Фильтруем только критичные
                $criticalMissing = [];
                foreach ($missingComponents as $component) {
                    if (strpos($component, 'request') !== false ||
                        strpos($component, 'config') !== false ||
                        strpos($component, 'db') !== false ||
                        strpos($component, 'url') !== false ||
                        strpos($component, 'cart') !== false) {
                        $criticalMissing[] = $component;
                    }
                }
                
                if (!empty($criticalMissing)) {
                    throw new Exception('Критичные компоненты недоступны: ' . implode(', ', $criticalMissing));
                }
            }
            
            // Логируем успешную инициализацию
            $this->logDebug('Components initialization completed', [
                'has_request' => is_object($this->request ?? null),
                'has_config' => is_object($this->config ?? null),
                'has_db' => is_object($this->db ?? null),
                'has_url' => is_object($this->url ?? null),
                'has_cart' => is_object($this->cart ?? null),
                'has_session' => is_object($this->session ?? null),
                'has_customer' => is_object($this->customer ?? null),
                'has_response' => is_object($this->response ?? null)
            ]);

        } catch (Exception $e) {
            $this->logError('Failed to initialize components', [
                'error' => $e->getMessage(),
                'trace' => $this->formatTrace($e->getTrace())
            ]);
            throw $e;
        }
    }

    /**
     * Инициализация необходимых моделей с проверкой
     *
     * @throws Exception
     */
    private function initializeModels() {
        try {
            // Проверка наличия loader через try-catch
            try {
                $this->load->model('checkout/order');
            } catch (Exception $e) {
                throw new Exception('Failed to load checkout/order model: ' . $e->getMessage());
            }

            // Проверка что модель загружена
            if (!is_object($this->model_checkout_order ?? null)) {
                throw new Exception('checkout/order model not loaded properly');
            }

            try {
                $this->load->model('localisation/currency');
            } catch (Exception $e) {
                throw new Exception('Failed to load localisation/currency model: ' . $e->getMessage());
            }

            if (!is_object($this->model_localisation_currency ?? null)) {
                throw new Exception('localisation/currency model not loaded properly');
            }

            $this->logDebug('Models initialized successfully');

        } catch (Exception $e) {
            $this->logError('Failed to initialize models', [
                'error' => $e->getMessage()
            ]);
            throw new Exception('Не удалось инициализировать необходимые модели: ' . $e->getMessage());
        }
    }

    /**
     * Валидация корзины
     *
     * @throws CartException
     */
    private function validateCart() {
        try {
            // Проверка объекта корзины через is_object с null coalescing
            if (!is_object($this->cart ?? null)) {
                $this->logError('Cart is not an object', [
                    'cart_type' => gettype($this->cart ?? null)
                ]);
                throw new CartException('Объект корзины не инициализирован');
            }

            // Проверка наличия метода hasProducts
            if (!method_exists($this->cart, 'hasProducts')) {
                throw new CartException('Корзина не поддерживает необходимые методы');
            }

            if (!$this->cart->hasProducts()) {
                throw new CartException('Корзина пуста');
            }

            // Проверка наличия метода getProducts
            if (!method_exists($this->cart, 'getProducts')) {
                throw new CartException('Невозможно получить товары из корзины');
            }

            $products = $this->cart->getProducts();

            if (empty($products)) {
                throw new CartException('Корзина не содержит товаров');
            }

            // Проверка наличия товаров на складе
            foreach ($products as $product) {
                if (!isset($product['product_id'])) {
                    throw new CartException('Некорректные данные товара в корзине');
                }

                // Проверка количества
                if (!isset($product['quantity']) || $product['quantity'] <= 0) {
                    throw new CartException('Некорректное количество товара');
                }

                // Проверка цены
                if (!isset($product['price']) || $product['price'] < 0) {
                    throw new CartException('Некорректная цена товара');
                }
            }

            // Проверка наличия метода getTotal
            if (!method_exists($this->cart, 'getTotal')) {
                throw new CartException('Невозможно получить сумму заказа');
            }

            // Проверка общей суммы
            $total = $this->cart->getTotal();

            if ($total <= 0) {
                throw new CartException('Некорректная сумма заказа');
            }

            if ($total > self::MAX_CART_TOTAL) {
                throw new CartException('Сумма заказа превышает максимально допустимую');
            }

            $this->logDebug('Cart validated successfully', [
                'products_count' => count($products),
                'total' => $total
            ]);

        } catch (CartException $e) {
            throw $e;
        } catch (Exception $e) {
            $this->logError('Unexpected error during cart validation', [
                'error' => $e->getMessage(),
                'trace' => $this->formatTrace($e->getTrace())
            ]);
            throw new CartException('Ошибка при проверке корзины: ' . $e->getMessage());
        }
    }

    /**
     * Валидация входных данных
     *
     * @return array Провалидированные данные
     * @throws ValidationException
     */
    private function validateInputData() {
        try {
            $data = [];

            // Проверка наличия объекта customer через try-catch
            $hasCustomer = false;
            $isLogged = false;
            
            try {
                $hasCustomer = is_object($this->customer ?? null);
                if ($hasCustomer) {
                    $isLogged = $this->customer->isLogged();
                }
            } catch (Exception $e) {
                $this->logWarning('Customer object not available', ['error' => $e->getMessage()]);
            }

            // Если пользователь авторизован
            if ($hasCustomer && $isLogged) {
                try {
                    $data['customer_id'] = $this->customer->getId();
                    $data['customer_group_id'] = $this->customer->getGroupId();
                    $data['firstname'] = $this->customer->getFirstName();
                    $data['lastname'] = $this->customer->getLastName();
                    $data['email'] = $this->customer->getEmail();
                    $data['telephone'] = $this->customer->getTelephone();

                    // Валидация данных авторизованного пользователя
                    if (empty($data['firstname'])) {
                        throw new ValidationException('Имя пользователя не найдено', 'firstname');
                    }

                    if (empty($data['email'])) {
                        throw new ValidationException('Email пользователя не найден', 'email');
                    }

                    if (empty($data['telephone'])) {
                        throw new ValidationException('Телефон пользователя не найден', 'telephone');
                    }

                    $this->logDebug('Using logged customer data', [
                        'customer_id' => $data['customer_id']
                    ]);

                    return $data;

                } catch (ValidationException $e) {
                    throw $e;
                } catch (Exception $e) {
                    $this->logError('Error retrieving customer data', [
                        'error' => $e->getMessage()
                    ]);
                    throw new ValidationException('Ошибка при получении данных пользователя', 'customer');
                }
            }

            // Гостевой заказ - валидация POST данных
            if (!isset($this->request->post) || !is_array($this->request->post)) {
                throw new ValidationException('Данные формы не получены', 'form');
            }

            $data['customer_id'] = 0;
            $data['customer_group_id'] = 1;

            // Валидация имени
            if (!isset($this->request->post['firstname'])) {
                throw new ValidationException('Поле "Имя" обязательно для заполнения', 'firstname');
            }

            $firstname = $this->sanitizeString($this->request->post['firstname']);

            if (empty($firstname)) {
                throw new ValidationException('Имя не может быть пустым', 'firstname');
            }

            if (mb_strlen($firstname) < self::MIN_NAME_LENGTH) {
                throw new ValidationException(
                    'Имя должно содержать минимум ' . self::MIN_NAME_LENGTH . ' символа',
                    'firstname'
                );
            }

            if (mb_strlen($firstname) > self::MAX_NAME_LENGTH) {
                throw new ValidationException(
                    'Имя слишком длинное (максимум ' . self::MAX_NAME_LENGTH . ' символов)',
                    'firstname'
                );
            }

            // Проверка на недопустимые символы
            if (preg_match('/[<>{}]/', $firstname)) {
                throw new ValidationException('Имя содержит недопустимые символы', 'firstname');
            }

            $data['firstname'] = $firstname;
            $data['lastname'] = '';

            // Валидация email
            $email = $this->sanitizeString($this->request->post['email'] ?? '');

            if (!empty($email)) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new ValidationException('Некорректный email адрес', 'email');
                }

                if (strlen($email) > 254) {
                    throw new ValidationException('Email слишком длинный', 'email');
                }

                // Дополнительная проверка email (опционально, может быть медленной)
                // if (!$this->isValidEmailDomain($email)) {
                //     throw new ValidationException('Домен email адреса недействителен', 'email');
                // }
            }

            $data['email'] = $email;

            // Валидация телефона
            if (!isset($this->request->post['telephone'])) {
                throw new ValidationException('Поле "Телефон" обязательно для заполнения', 'telephone');
            }

            $telephone = $this->sanitizePhone($this->request->post['telephone']);

            if (empty($telephone)) {
                throw new ValidationException('Телефон не может быть пустым', 'telephone');
            }

            $digitsOnly = preg_replace('/\D/', '', $telephone);

            if (strlen($digitsOnly) < self::MIN_PHONE_LENGTH) {
                throw new ValidationException(
                    'Телефон должен содержать минимум ' . self::MIN_PHONE_LENGTH . ' цифр',
                    'telephone'
                );
            }

            if (strlen($digitsOnly) > 18) {
                throw new ValidationException('Телефон слишком длинный', 'telephone');
            }

            $data['telephone'] = $telephone;

            // Валидация согласия
            if (!isset($this->request->post['agreement']) || $this->request->post['agreement'] !== 'on') {
                throw new ValidationException(
                    'Для отправки заявки необходимо дать согласие на обработку персональных данных',
                    'agreement'
                );
            }

            $this->logDebug('Guest data validated successfully', [
                'has_firstname' => !empty($data['firstname']),
                'has_email' => !empty($data['email']),
                'has_telephone' => !empty($data['telephone'])
            ]);

            return $data;

        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            $this->logError('Unexpected error during input validation', [
                'error' => $e->getMessage(),
                'trace' => $this->formatTrace($e->getTrace())
            ]);
            throw new ValidationException('Ошибка при проверке данных формы', 'form');
        }
    }

    /**
     * Валидация данных заказа перед созданием
     *
     * @param array $orderData
     * @throws ValidationException
     */
    private function validateOrderData($orderData) {
        try {
            // Проверка обязательных полей
            $requiredFields = [
                'firstname', 'telephone', 'email', 'currency_code',
                'store_id', 'language_id', 'total', 'products'
            ];

            foreach ($requiredFields as $field) {
                if (!isset($orderData[$field])) {
                    throw new ValidationException(
                        "Отсутствует обязательное поле: {$field}",
                        $field
                    );
                }
            }

            // Проверка товаров
            if (empty($orderData['products'])) {
                throw new ValidationException('Заказ не содержит товаров', 'products');
            }

            // Проверка суммы
            if ($orderData['total'] <= 0) {
                throw new ValidationException('Некорректная сумма заказа', 'total');
            }

            // Проверка валюты
            if (empty($orderData['currency_code'])) {
                throw new ValidationException('Не указана валюта заказа', 'currency');
            }

            $this->logDebug('Order data validated successfully', [
                'customer_id' => $orderData['customer_id'],
                'products_count' => count($orderData['products']),
                'total' => $orderData['total']
            ]);

        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            $this->logError('Unexpected error during order data validation', [
                'error' => $e->getMessage()
            ]);
            throw new ValidationException('Ошибка при проверке данных заказа', 'order');
        }
    }

    // ... (остальные методы остаются без изменений)
    // Копируйте все остальные методы из предыдущей версии

    /**
     * Подготовка данных заказа
     */
    private function prepareOrderData($validatedData) {
        try {
            $orderData = [];

            // Основные данные клиента
            $orderData['customer_id'] = $validatedData['customer_id'];
            $orderData['customer_group_id'] = $validatedData['customer_group_id'];
            $orderData['firstname'] = $validatedData['firstname'];
            $orderData['lastname'] = $validatedData['lastname'];
            $orderData['email'] = $validatedData['email'];
            $orderData['telephone'] = $validatedData['telephone'];

            // Данные оплаты и доставки
            $orderData = array_merge($orderData, $this->prepareAddressData($validatedData));

            // Способ оплаты и доставки
            $orderData['payment_method'] = $this->getPaymentMethod();
            $orderData['payment_code'] = 'cod';
            $orderData['shipping_method'] = $this->getShippingMethod();
            $orderData['shipping_code'] = 'none';

            // Валюта
            $orderData = array_merge($orderData, $this->prepareCurrencyData());

            // Данные магазина
            $orderData = array_merge($orderData, $this->prepareStoreData());

            // Системная информация
            $orderData = array_merge($orderData, $this->prepareSystemData());

            // Комментарий
            $orderData['comment'] = $this->sanitizeString($this->request->post['comment'] ?? '');

            // Проверка наличия метода getProducts
            if (!method_exists($this->cart, 'getProducts')) {
                throw new Exception('Cart method getProducts not available');
            }

            // Товары
            $orderData['products'] = $this->cart->getProducts();

            if (empty($orderData['products'])) {
                throw new Exception('Не удалось получить товары из корзины');
            }

            // Проверка наличия метода getTotal
            if (!method_exists($this->cart, 'getTotal')) {
                throw new Exception('Cart method getTotal not available');
            }

            // Итоговая сумма
            $orderData['total'] = $this->cart->getTotal();

            if ($orderData['total'] <= 0) {
                throw new Exception('Некорректная сумма заказа после подготовки');
            }

            // Totals
            $orderData['totals'] = $this->prepareTotals($orderData['total']);

            // Даты
            $orderData['date_added'] = date('Y-m-d H:i:s');
            $orderData['date_modified'] = date('Y-m-d H:i:s');

            $this->logDebug('Order data prepared successfully', [
                'customer_id' => $orderData['customer_id'],
                'products_count' => count($orderData['products']),
                'total' => $orderData['total']
            ]);

            return $orderData;

        } catch (Exception $e) {
            $this->logError('Error preparing order data', [
                'error' => $e->getMessage(),
                'trace' => $this->formatTrace($e->getTrace())
            ]);
            throw new Exception('Ошибка при подготовке данных заказа: ' . $e->getMessage());
        }
    }

    /**
     * Очистка корзины с обработкой ошибок
     */
    private function clearCart() {
        try {
            // Проверка наличия метода clear
            if (!method_exists($this->cart, 'clear')) {
                $this->logWarning('Cart method clear not available');
                return;
            }

            $this->cart->clear();

            $this->logDebug('Cart cleared successfully');

            // Проверяем что корзина действительно очищена
            if (method_exists($this->cart, 'hasProducts') && $this->cart->hasProducts()) {
                $this->logWarning('Cart still has products after clear');
                // Не выбрасываем исключение, так как заказ уже создан
            }

        } catch (Exception $e) {
            $this->logError('Failed to clear cart', [
                'error' => $e->getMessage()
            ]);
            // Не выбрасываем исключение, так как заказ уже создан
        }
    }

    /**
     * Подготовка адресных данных
     *
     * @param array $validatedData
     * @return array
     */
    private function prepareAddressData($validatedData) {
        $addressData = [];

        foreach (['payment', 'shipping'] as $type) {
            $addressData["{$type}_firstname"] = $validatedData['firstname'];
            $addressData["{$type}_lastname"] = $validatedData['lastname'];
            $addressData["{$type}_company"] = '';
            $addressData["{$type}_address_1"] = '';
            $addressData["{$type}_address_2"] = '';
            $addressData["{$type}_city"] = '';
            $addressData["{$type}_postcode"] = '';
            $addressData["{$type}_country"] = '';
            $addressData["{$type}_country_id"] = 0;
            $addressData["{$type}_zone"] = '';
            $addressData["{$type}_zone_id"] = 0;
        }

        return $addressData;
    }

    /**
     * Подготовка данных валюты
     *
     * @return array
     * @throws Exception
     */
    private function prepareCurrencyData() {
        try {
            $currencyCode = $this->session->data['currency'] ?? $this->config->get('config_currency');

            if (empty($currencyCode)) {
                throw new Exception('Currency code is empty');
            }

            $currencyInfo = $this->model_localisation_currency->getCurrencyByCode($currencyCode);

            if ($currencyInfo && isset($currencyInfo['currency_id'])) {
                $this->logDebug('Currency data loaded', [
                    'code' => $currencyInfo['code'],
                    'value' => $currencyInfo['value']
                ]);

                return [
                    'currency_id' => $currencyInfo['currency_id'],
                    'currency_code' => $currencyInfo['code'],
                    'currency_value' => $currencyInfo['value']
                ];
            }

            $this->logWarning('Currency not found, using defaults', [
                'requested_currency' => $currencyCode
            ]);

            $defaultCurrency = $this->config->get('config_currency');

            if (empty($defaultCurrency)) {
                throw new Exception('Default currency is not configured');
            }

            return [
                'currency_id' => 0,
                'currency_code' => $defaultCurrency,
                'currency_value' => 1.00000000
            ];

        } catch (Exception $e) {
            $this->logError('Error preparing currency data', [
                'error' => $e->getMessage()
            ]);
            throw new Exception('Ошибка при подготовке данных валюты');
        }
    }

    /**
     * Подготовка данных магазина
     *
     * @return array
     * @throws Exception
     */
    private function prepareStoreData() {
        try {
            $storeId = $this->config->get('config_store_id') ?? 0;
            $storeName = $this->config->get('config_name');

            if (empty($storeName)) {
                throw new Exception('Store name is not configured');
            }

            return [
                'store_id' => $storeId,
                'store_name' => $storeName,
                'store_url' => $storeId ? $this->config->get('config_url') : (defined('HTTP_SERVER') ? HTTP_SERVER : ''),
                'invoice_prefix' => $this->config->get('config_invoice_prefix') ?? ''
            ];

        } catch (Exception $e) {
            $this->logError('Error preparing store data', [
                'error' => $e->getMessage()
            ]);
            throw new Exception('Ошибка при подготовке данных магазина');
        }
    }

    /**
     * Подготовка системных данных
     *
     * @return array
     */
    private function prepareSystemData() {
        return [
            'language_id' => $this->config->get('config_language_id') ?? 1,
            'ip' => $this->getClientIp(),
            'forwarded_ip' => $this->getForwardedIp(),
            'user_agent' => $this->getUserAgent(),
            'accept_language' => $this->request->server['HTTP_ACCEPT_LANGUAGE'] ?? '',
            'affiliate_id' => 0,
            'commission' => 0,
            'marketing_id' => 0,
            'tracking' => ''
        ];
    }

    /**
     * Подготовка итоговых сумм
     *
     * @param float $total
     * @return array
     */
    private function prepareTotals($total) {
        return [
            [
                'code' => 'sub_total',
                'title' => 'Промежуточный итог',
                'value' => $total,
                'sort_order' => 1
            ],
            [
                'code' => 'total',
                'title' => 'Итого',
                'value' => $total,
                'sort_order' => 9
            ]
        ];
    }

    /**
     * Создание заказа
     *
     * @param array $orderData
     * @return int ID заказа
     * @throws DatabaseException
     */
    private function createOrder($orderData) {
        $this->beginTransaction();

        try {
            $this->logDebug('Creating order in database', [
                'customer_id' => $orderData['customer_id'],
                'total' => $orderData['total']
            ]);

            $orderId = $this->model_checkout_order->addOrder($orderData);

            if (!$orderId || $orderId <= 0) {
                throw new DatabaseException(
                    'Не удалось создать заказ. Метод addOrder вернул невалидный ID.',
                    ''
                );
            }

            // Проверяем что заказ действительно создан
            if (!$this->verifyOrderExists($orderId)) {
                throw new DatabaseException(
                    'Заказ не найден в базе данных после создания',
                    ''
                );
            }

            $this->commitTransaction();

            $this->logInfo('Order record created successfully', [
                'order_id' => $orderId,
                'customer_id' => $orderData['customer_id'],
                'total' => $orderData['total']
            ]);

            return $orderId;

        } catch (DatabaseException $e) {
            $this->rollbackTransaction();
            throw $e;

        } catch (Exception $e) {
            $this->rollbackTransaction();

            $this->logError('Failed to create order', [
                'error' => $e->getMessage(),
                'customer_id' => $orderData['customer_id'],
                'trace' => $this->formatTrace($e->getTrace())
            ]);

            throw new DatabaseException(
                'Ошибка при создании заказа в базе данных',
                '',
                $e
            );
        }
    }

    /**
     * Проверка существования заказа в БД
     *
     * @param int $orderId
     * @return bool
     */
    private function verifyOrderExists($orderId) {
        try {
            $query = $this->db->query("SELECT order_id FROM `" . DB_PREFIX . "order` WHERE order_id = " . (int)$orderId);
            return isset($query->row['order_id']);
        } catch (Exception $e) {
            $this->logWarning('Failed to verify order exists', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Действия после создания заказа
     *
     * @param int $orderId
     * @param array $orderData
     */
    private function afterOrderCreated($orderId, $orderData) {
        try {
            // Здесь можно добавить дополнительную логику:
            // - Отправка уведомлений
            // - Обновление статистики
            // - Интеграция с внешними системами

            $this->logDebug('After order created actions executed', [
                'order_id' => $orderId
            ]);

        } catch (Exception $e) {
            // Не критичная ошибка, только логируем
            $this->logWarning('Error in afterOrderCreated', [
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Получение способа оплаты
     *
     * @return string
     */
    private function getPaymentMethod() {
        return $this->sanitizeString($this->request->post['payment_method'] ?? 'Безналичная оплата');
    }

    /**
     * Получение способа доставки
     *
     * @return string
     */
    private function getShippingMethod() {
        return $this->sanitizeString($this->request->post['shipping_method'] ?? 'Без доставки');
    }

    /**
     * Проверка валидности домена email
     *
     * @param string $email
     * @return bool
     */
    private function isValidEmailDomain($email) {
        try {
            $parts = explode('@', $email);

            if (count($parts) !== 2) {
                return false;
            }

            $domain = $parts[1];

            // Проверка DNS записей (может быть медленной)
            return checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A');
        } catch (Exception $e) {
            $this->logWarning('Email domain validation failed', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            return true; // Возвращаем true чтобы не блокировать при ошибке DNS
        }
    }

    /**
     * Начало транзакции БД
     */
    private function beginTransaction() {
        if (!isset($this->db)) {
            $this->logWarning('DB object not available for transaction');
            return;
        }

        if (method_exists($this->db, 'query')) {
            try {
                $this->db->query("START TRANSACTION");
                $this->logDebug('Database transaction started');
            } catch (Exception $e) {
                $this->logWarning('Failed to start transaction', [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Подтверждение транзакции БД
     */
    private function commitTransaction() {
        if (!isset($this->db)) {
            $this->logWarning('DB object not available for commit');
            return;
        }

        if (method_exists($this->db, 'query')) {
            try {
                $this->db->query("COMMIT");
                $this->logDebug('Database transaction committed');
            } catch (Exception $e) {
                $this->logError('Failed to commit transaction', [
                    'error' => $e->getMessage()
                ]);
                throw new DatabaseException('Ошибка при подтверждении транзакции', '');
            }
        }
    }

    /**
     * Откат транзакции БД
     */
    private function rollbackTransaction() {
        if (!isset($this->db)) {
            $this->logWarning('DB object not available for rollback');
            return;
        }

        if (method_exists($this->db, 'query')) {
            try {
                $this->db->query("ROLLBACK");
                $this->logDebug('Database transaction rolled back');
            } catch (Exception $e) {
                $this->logError('Failed to rollback transaction', [
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Уведомление администратора о критической ошибке
     *
     * @param Throwable $exception
     * @param string $requestId
     */
    private function notifyAdminAboutError($exception, $requestId) {
        try {
            $adminEmail = $this->config->get('config_email');

            if (empty($adminEmail)) {
                $this->logDebug('Admin email not configured, skipping notification');
                return;
            }

            $subject = 'Критическая ошибка на сайте: ' . $this->config->get('config_name');

            $message = "Произошла критическая ошибка при оформлении заказа\n\n";
            $message .= "Request ID: {$requestId}\n";
            $message .= "Время: " . date('Y-m-d H:i:s') . "\n";
            $message .= "IP: " . $this->getClientIp() . "\n";
            $message .= "User Agent: " . $this->getUserAgent() . "\n\n";
            $message .= "Ошибка: " . $exception->getMessage() . "\n";
            $message .= "Файл: " . $exception->getFile() . "\n";
            $message .= "Строка: " . $exception->getLine() . "\n\n";
            $message .= "Trace:\n" . $exception->getTraceAsString();

            // Отправка email (с обработкой ошибок чтобы не создать рекурсию)
            try {
                // Проверка наличия класса Mail
                if (!class_exists('Mail')) {
                    $this->logWarning('Mail class not available');
                    return;
                }

                $mail = new Mail();
                $mail->protocol = $this->config->get('config_mail_protocol');
                $mail->parameter = $this->config->get('config_mail_parameter');
                $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
                $mail->smtp_username = $this->config->get('config_mail_smtp_username');
                $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
                $mail->smtp_port = $this->config->get('config_mail_smtp_port');
                $mail->smtp_timeout = 5; // Короткий timeout чтобы не блокировать ответ

                $mail->setTo($adminEmail);
                $mail->setFrom($this->config->get('config_mail_smtp_username'));
                $mail->setSender($this->config->get('config_name'));
                $mail->setSubject($subject);
                $mail->setText($message);
                
                // Используем @ чтобы подавить ошибки отправки
                @$mail->send();

                $this->logInfo('Admin notification sent', [
                    'request_id' => $requestId
                ]);

            } catch (Exception $e) {
                // Логируем но не останавливаем выполнение
                $this->logWarning('Failed to send admin notification (non-critical)', [
                    'error' => $e->getMessage()
                ]);
            }

        } catch (Exception $e) {
            // Полностью игнорируем ошибки уведомлений
            $this->logWarning('Error in notifyAdminAboutError (ignored)', [
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Отправка JSON ответа
     *
     * @param array $data
     * @param int $httpCode
     */
    private function sendJsonResponse($data, $httpCode = 200) {
        try {
            // Проверка объекта response через is_object
            if (is_object($this->response ?? null)) {
                $this->response->addHeader('Content-Type: application/json');
                $this->response->addHeader('HTTP/1.1 ' . $httpCode);
                $this->response->setOutput(json_encode($data, JSON_UNESCAPED_UNICODE));
                $this->logDebug('Response sent via response object');
            } else {
                // Fallback на прямую отправку headers
                $this->logWarning('Response object not available, using direct headers');
                
                if (!headers_sent()) {
                    header('Content-Type: application/json');
                    header('HTTP/1.1 ' . $httpCode);
                }
                
                echo json_encode($data, JSON_UNESCAPED_UNICODE);
                exit;
            }
        } catch (Exception $e) {
            // Критическая ошибка - последняя попытка отправить ответ
            $this->logError('Failed to send response', [
                'error' => $e->getMessage()
            ]);
            
            if (!headers_sent()) {
                header('Content-Type: application/json');
                header('HTTP/1.1 500');
            }
            
            echo json_encode([
                'error' => true,
                'message' => 'Критическая ошибка сервера'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    /**
     * Sanitization строк
     *
     * @param string $str
     * @return string
     */
    private function sanitizeString($str) {
        if (!is_string($str)) {
            return '';
        }

        return trim(strip_tags($str));
    }

    /**
     * Sanitization телефона
     *
     * @param string $phone
     * @return string
     */
    private function sanitizePhone($phone) {
        if (!is_string($phone)) {
            return '';
        }

        return trim(preg_replace('/[^\d\s\-\+\(\)]/', '', $phone));
    }

    /**
     * Форматирование trace для логов
     *
     * @param array $trace
     * @return array
     */
    private function formatTrace($trace) {
        $formatted = [];

        foreach (array_slice($trace, 0, 5) as $item) {
            $formatted[] = [
                'file' => $item['file'] ?? 'unknown',
                'line' => $item['line'] ?? 0,
                'function' => $item['function'] ?? 'unknown',
                'class' => $item['class'] ?? ''
            ];
        }

        return $formatted;
    }

    /**
     * Получение IP клиента (безопасная версия)
     *
     * @return string
     */
    private function getClientIp() {
        try {
            // Пытаемся получить через request object
            if (is_object($this->request ?? null) && 
                isset($this->request->server['REMOTE_ADDR'])) {
                return $this->request->server['REMOTE_ADDR'];
            }
        } catch (Exception $e) {
            // Ignore
        }
        
        // Fallback на $_SERVER
        if (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        
        return '0.0.0.0';
    }

    /**
     * Получение forwarded IP
     *
     * @return string
     */
    private function getForwardedIp() {
        try {
            if (is_object($this->request ?? null)) {
                if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
                    return $this->request->server['HTTP_X_FORWARDED_FOR'];
                }

                if (!empty($this->request->server['HTTP_CLIENT_IP'])) {
                    return $this->request->server['HTTP_CLIENT_IP'];
                }
            }
        } catch (Exception $e) {
            // Ignore
        }
        
        // Fallback на $_SERVER
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        return '';
    }

    /**
     * Получение User Agent (безопасная версия)
     *
     * @return string
     */
    private function getUserAgent() {
        try {
            // Пытаемся получить через request object
            if (is_object($this->request ?? null) && 
                isset($this->request->server['HTTP_USER_AGENT'])) {
                return $this->request->server['HTTP_USER_AGENT'];
            }
        } catch (Exception $e) {
            // Ignore
        }
        
        // Fallback на $_SERVER
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        }
        
        return '';
    }

    // =========================================================================
    // ЛОГИРОВАНИЕ
    // =========================================================================

    /**
     * Логирование debug сообщений
     *
     * @param string $message
     * @param array $context
     */
    private function logDebug($message, $context = []) {
        $this->writeLog('DEBUG', $message, $context);
    }

    /**
     * Логирование информационных сообщений
     *
     * @param string $message
     * @param array $context
     */
    private function logInfo($message, $context = []) {
        $this->writeLog('INFO', $message, $context);
    }

    /**
     * Логирование предупреждений
     *
     * @param string $message
     * @param array $context
     */
    private function logWarning($message, $context = []) {
        $this->writeLog('WARNING', $message, $context);
    }

    /**
     * Логирование ошибок
     *
     * @param string $message
     * @param array $context
     */
    private function logError($message, $context = []) {
        $this->writeLog('ERROR', $message, $context);
        $this->writeLog('ERROR', $message, $context, self::ERROR_LOG_FILE);
    }

    /**
     * Запись в лог
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @param string $logFile
     */
    private function writeLog($level, $message, $context = [], $logFile = self::LOG_FILE) {
        try {
            $timestamp = date('Y-m-d H:i:s');
            $ip = $this->getClientIp();

            $logEntry = sprintf(
                "[%s] [%s] [IP: %s] %s",
                $timestamp,
                $level,
                $ip,
                $message
            );

            if (!empty($context)) {
                $safeContext = $this->filterSensitiveData($context);
                $logEntry .= ' | ' . json_encode($safeContext, JSON_UNESCAPED_UNICODE);
            }

            $logEntry .= PHP_EOL;

            // Попытка использовать встроенное логирование OpenCart
            if (isset($this->log) && is_object($this->log) && method_exists($this->log, 'write')) {
                $this->log->write($logEntry);
            } else {
                // Fallback на прямую запись в файл
                if (defined('DIR_LOGS')) {
                    $logFilePath = DIR_LOGS . $logFile;
                    @file_put_contents($logFilePath, $logEntry, FILE_APPEND | LOCK_EX);
                }
            }

        } catch (Exception $e) {
            // Тихо игнорируем ошибки логирования
            // В критической ситуации пытаемся записать в error_log PHP
            @error_log('Failed to write to custom log: ' . $e->getMessage());
        }
    }

    /**
     * Фильтрация чувствительных данных
     *
     * @param array $context
     * @return array
     */
    private function filterSensitiveData($context) {
        if (!is_array($context)) {
            return [];
        }

        $sensitiveKeys = ['password', 'card', 'cvv', 'secret', 'token', 'api_key', 'smtp_password'];

        foreach ($context as $key => $value) {
            foreach ($sensitiveKeys as $sensitiveKey) {
                if (stripos($key, $sensitiveKey) !== false) {
                    $context[$key] = '[FILTERED]';
                }
            }

            if (is_array($value)) {
                $context[$key] = $this->filterSensitiveData($value);
            }
        }

        return $context;
    }
}

// =============================================================================
// CUSTOM EXCEPTIONS
// =============================================================================

/**
 * Исключение для ошибок валидации
 */
class ValidationException extends Exception {
    private $field;

    public function __construct($message, $field = '', $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
        $this->field = $field;
    }

    public function getField() {
        return $this->field;
    }
}

/**
 * Исключение для ошибок корзины
 */
class CartException extends Exception {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}

/**
 * Исключение для ошибок базы данных
 */
class DatabaseException extends Exception {
    private $query;

    public function __construct($message, $query = '', $previous = null) {
        parent::__construct($message, 0, $previous);
        $this->query = $query;
    }

    public function getQuery() {
        return $this->query;
    }
}