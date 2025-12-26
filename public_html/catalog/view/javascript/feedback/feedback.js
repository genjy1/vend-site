'use strict';

/**
 * Feedback Forms Handler
 * Обработка форм обратной связи с валидацией, логированием и error handling
 * 
 * Улучшения:
 * - Structured logging с уровнями и контекстом
 * - Комплексная валидация форм
 * - Retry logic с exponential backoff
 * - Timeout для API запросов
 * - Request ID для трассировки
 * - Graceful degradation
 * - XSS защита
 * - Performance tracking
 */

/* ============================================================
   КОНФИГУРАЦИЯ
============================================================ */
const CONFIG = {
    toast: {
        stackLimit: 5,
        duration: 4000,
        baseYMobile: 10,
        baseYDesktop: 20,
        spacing: 70
    },
    
    api: {
        endpoint: 'index.php?route=common/feedback',
        timeout: 15000,        // 15 секунд
        retryAttempts: 2,      // 2 попытки повтора
        retryDelay: 1000       // 1 секунда между попытками
    },
    
    calltouch: {
        siteId: '49728',
        endpoint: 'https://api.calltouch.ru/calls-service/RestAPI/requests/',
        timeout: 10000         // 10 секунд
    },
    
    validation: {
        minNameLength: 2,
        maxNameLength: 100,
        minPhoneDigits: 10,
        maxPhoneDigits: 18,
        emailRegex: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
        phoneRegex: /^[\d\s\-\+\(\)]+$/
    },
    
    breakpoints: {
        mobile: 768
    },
    
    logging: {
        enabled: true,
        levels: ['error', 'warn', 'info', 'debug']
    }
};

/* ============================================================
   УТИЛИТЫ
============================================================ */

/**
 * Генерация уникального Request ID
 */
const generateRequestId = () => {
    const timestamp = Date.now();
    const random = Math.random().toString(36).substring(2, 10);
    return `${timestamp}-${random}`;
};

/**
 * Проверка мобильного устройства
 */
const isMobile = () => window.matchMedia(`(max-width: ${CONFIG.breakpoints.mobile}px)`).matches;

/**
 * Sanitization строк (защита от XSS)
 */
const sanitizeString = (str) => {
    if (typeof str !== 'string') return '';
    
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML.trim();
};

/**
 * Sanitization телефона
 */
const sanitizePhone = (phone) => {
    if (typeof phone !== 'string') return '';
    return phone.replace(/[^\d\s\-\+\(\)]/g, '').trim();
};

/**
 * Форматирование длительности
 */
const formatDuration = (ms) => {
    return `${Math.round(ms)}ms`;
};

/* ============================================================
   ЛОГИРОВАНИЕ
============================================================ */
class Logger {
    constructor() {
        this.enabled = CONFIG.logging.enabled;
        this.levels = CONFIG.logging.levels;
    }

    _log(level, message, context = {}) {
        if (!this.enabled || !this.levels.includes(level)) return;

        const timestamp = new Date().toISOString();
        const logEntry = {
            timestamp,
            level: level.toUpperCase(),
            message,
            ...context
        };

        const consoleMethod = level === 'error' ? 'error' : 
                             level === 'warn' ? 'warn' : 'log';
        
        console[consoleMethod](`[${timestamp}] [${level.toUpperCase()}]`, message, context);

        // Отправка критичных ошибок на сервер (опционально)
        if (level === 'error' && context.critical) {
            this._sendErrorToServer(logEntry);
        }
    }

    debug(message, context = {}) {
        this._log('debug', message, context);
    }

    info(message, context = {}) {
        this._log('info', message, context);
    }

    warn(message, context = {}) {
        this._log('warn', message, context);
    }

    error(message, context = {}) {
        this._log('error', message, context);
    }

    _sendErrorToServer(logEntry) {
        // Асинхронная отправка ошибок на сервер (не блокирует UI)
        try {
            navigator.sendBeacon('/log-error', JSON.stringify(logEntry));
        } catch (e) {
            // Ignore
        }
    }
}

const logger = new Logger();

/* ============================================================
   TOASTIFY
============================================================ */
class ToastManager {
    constructor() {
        this.activeToasts = [];
        this.stackLimit = CONFIG.toast.stackLimit;
        
        window.addEventListener('resize', () => this.clearAll());
    }

    show(msg, type = 'success') {
        logger.debug('Showing toast', { message: msg, type });

        if (this.activeToasts.length >= this.stackLimit) {
            const oldest = this.activeToasts.shift();
            oldest?.remove();
        }

        const colors = {
            success: '#4a259b',
            error: '#dc3545',
            warning: '#ffc107',
            info: '#17a2b8'
        };

        const baseY = isMobile() ? CONFIG.toast.baseYMobile : CONFIG.toast.baseYDesktop;

        const toast = Toastify({
            text: msg,
            duration: CONFIG.toast.duration,
            gravity: isMobile() ? 'bottom' : 'top',
            position: isMobile() ? 'center' : 'right',
            stopOnFocus: true,
            offset: { 
                x: isMobile() ? 0 : 20, 
                y: baseY + this.activeToasts.length * CONFIG.toast.spacing 
            },
            style: {
                background: colors[type] || colors.success,
                padding: isMobile() ? '14px 18px' : '18px 22px',
                borderRadius: '10px',
                fontSize: isMobile() ? '14px' : '15px',
                maxWidth: isMobile() ? '90%' : '340px',
                textAlign: 'center',
                boxShadow: '0 4px 10px rgba(0,0,0,0.15)',
                color: 'white',
            },
            callback: () => {
                this.activeToasts = this.activeToasts.filter(t => t !== toast.el);
            }
        });

        toast.showToast();
        this.activeToasts.push(toast.el);
    }

    success(msg) {
        this.show(msg, 'success');
    }

    error(msg) {
        this.show(msg, 'error');
    }

    warning(msg) {
        this.show(msg, 'warning');
    }

    info(msg) {
        this.show(msg, 'info');
    }

    clearAll() {
        logger.debug('Clearing all toasts');
        this.activeToasts.forEach(t => t.remove());
        this.activeToasts = [];
    }
}

const toast = new ToastManager();

/* ============================================================
   ВАЛИДАЦИЯ
============================================================ */
class Validator {
    static validateName(name) {
        const sanitized = sanitizeString(name);
        
        if (!sanitized || sanitized.length < CONFIG.validation.minNameLength) {
            return {
                valid: false,
                error: `Имя должно содержать минимум ${CONFIG.validation.minNameLength} символа`
            };
        }

        if (sanitized.length > CONFIG.validation.maxNameLength) {
            return {
                valid: false,
                error: `Имя слишком длинное (максимум ${CONFIG.validation.maxNameLength} символов)`
            };
        }

        if (/<|>|{|}/.test(sanitized)) {
            return {
                valid: false,
                error: 'Имя содержит недопустимые символы'
            };
        }

        return { valid: true, value: sanitized };
    }

    static validateEmail(email) {
        const sanitized = sanitizeString(email).toLowerCase();

        if (!sanitized) {
            return { valid: true, value: '' }; // Email опционален
        }

        if (!CONFIG.validation.emailRegex.test(sanitized)) {
            return {
                valid: false,
                error: 'Некорректный email адрес'
            };
        }

        if (sanitized.length > 254) {
            return {
                valid: false,
                error: 'Email слишком длинный'
            };
        }

        return { valid: true, value: sanitized };
    }

    static validatePhone(phone) {
        const sanitized = sanitizePhone(phone);
        const digitsOnly = sanitized.replace(/\D/g, '');

        if (digitsOnly.length < CONFIG.validation.minPhoneDigits) {
            return {
                valid: false,
                error: `Телефон должен содержать минимум ${CONFIG.validation.minPhoneDigits} цифр`
            };
        }

        if (digitsOnly.length > CONFIG.validation.maxPhoneDigits) {
            return {
                valid: false,
                error: `Телефон слишком длинный (максимум ${CONFIG.validation.maxPhoneDigits} цифр)`
            };
        }

        if (!CONFIG.validation.phoneRegex.test(sanitized)) {
            return {
                valid: false,
                error: 'Телефон содержит недопустимые символы'
            };
        }

        return { valid: true, value: sanitized };
    }

    static validateAgreement(checked) {
        if (!checked) {
            return {
                valid: false,
                error: 'Для отправки заявки необходимо дать согласие на обработку персональных данных'
            };
        }

        return { valid: true };
    }
}

/* ============================================================
   API CLIENT
============================================================ */
class ApiClient {
    /**
     * Fetch с timeout
     */
    static async fetchWithTimeout(url, options = {}, timeout = CONFIG.api.timeout) {
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), timeout);

        try {
            const response = await fetch(url, {
                ...options,
                signal: controller.signal
            });
            clearTimeout(timeoutId);
            return response;
        } catch (error) {
            clearTimeout(timeoutId);
            if (error.name === 'AbortError') {
                throw new Error(`Request timeout after ${timeout}ms`);
            }
            throw error;
        }
    }

    /**
     * Retry logic с exponential backoff
     */
    static async fetchWithRetry(url, options = {}, attempts = CONFIG.api.retryAttempts) {
        let lastError;

        for (let i = 0; i <= attempts; i++) {
            try {
                logger.debug('API request attempt', { attempt: i + 1, url });
                
                const response = await this.fetchWithTimeout(url, options);
                
                logger.debug('API request successful', { 
                    attempt: i + 1, 
                    status: response.status 
                });
                
                return response;

            } catch (error) {
                lastError = error;
                
                logger.warn('API request failed', {
                    attempt: i + 1,
                    error: error.message
                });

                if (i < attempts) {
                    const delay = CONFIG.api.retryDelay * Math.pow(2, i); // Exponential backoff
                    logger.debug('Retrying after delay', { delay });
                    await new Promise(resolve => setTimeout(resolve, delay));
                }
            }
        }

        throw lastError;
    }

    /**
     * Отправка данных на сервер
     */
    static async sendFeedback(data, requestId) {
        const startTime = performance.now();

        try {
            logger.info('Sending feedback', { requestId, template: data.template });

            const response = await this.fetchWithRetry(
                CONFIG.api.endpoint,
                {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Request-ID': requestId
                    },
                    body: new URLSearchParams({ data: JSON.stringify(data) })
                }
            );

            const duration = performance.now() - startTime;

            if (!response.ok) {
                logger.error('API returned error status', {
                    requestId,
                    status: response.status,
                    statusText: response.statusText,
                    duration: formatDuration(duration)
                });

                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();

            logger.info('Feedback sent successfully', {
                requestId,
                duration: formatDuration(duration),
                response: result
            });

            return { success: true, data: result, duration };

        } catch (error) {
            const duration = performance.now() - startTime;

            logger.error('Failed to send feedback', {
                requestId,
                error: error.message,
                duration: formatDuration(duration),
                critical: true
            });

            throw error;
        }
    }

    /**
     * Отправка в Calltouch
     */
    static async sendCalltouch(data, requestId) {
        if (!window.jQuery) {
            logger.warn('jQuery not available, skipping Calltouch', { requestId });
            return;
        }

        if (!window.call_value) {
            logger.warn('Calltouch session ID not available', { requestId });
        }

        try {
            logger.debug('Sending Calltouch data', { requestId });

            const calltouchData = {
                fio: data.name,
                phoneNumber: data.phone,
                email: data.email || '',
                subject: data.subject || '',
                sessionId: window.call_value || '',
                requestUrl: location.href,
                comment: data.note || ''
            };

            await new Promise((resolve, reject) => {
                const timeoutId = setTimeout(() => {
                    reject(new Error('Calltouch timeout'));
                }, CONFIG.calltouch.timeout);

                jQuery.ajax({
                    url: `${CONFIG.calltouch.endpoint}${CONFIG.calltouch.siteId}/register/`,
                    type: 'POST',
                    dataType: 'json',
                    data: calltouchData,
                    success: (response) => {
                        clearTimeout(timeoutId);
                        logger.debug('Calltouch data sent', { requestId, response });
                        resolve(response);
                    },
                    error: (xhr, status, error) => {
                        clearTimeout(timeoutId);
                        reject(new Error(`Calltouch error: ${error}`));
                    }
                });
            });

        } catch (error) {
            // Не критично если Calltouch не отправился
            logger.warn('Failed to send Calltouch data (non-critical)', {
                requestId,
                error: error.message
            });
        }
    }
}

/* ============================================================
   UI HELPERS
============================================================ */
class UIHelper {
    /**
     * Подсветка поля с ошибкой
     */
    static highlightField(field, message) {
        if (!field) return;

        field.classList.add('field-error');
        
        if (navigator.vibrate) {
            navigator.vibrate(100);
        }

        // Показываем сообщение об ошибке
        const errorEl = field.parentElement?.querySelector('.error-message');
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.display = 'block';
        }

        setTimeout(() => {
            field.classList.remove('field-error');
            if (errorEl) {
                errorEl.style.display = 'none';
            }
        }, 3000);

        field.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /**
     * Подсветка чекбокса согласия
     */
    static highlightAgreement(checkbox) {
        if (!checkbox) return;

        checkbox.classList.add('checkbox-error');
        
        if (navigator.vibrate) {
            navigator.vibrate(100);
        }

        setTimeout(() => checkbox.classList.remove('checkbox-error'), 1500);
        checkbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    /**
     * Блокировка кнопки отправки
     */
    static disableSubmitButtons(form, text = 'Отправка...') {
        const buttons = form.querySelectorAll('button[type="submit"]');
        buttons.forEach(btn => {
            btn.disabled = true;
            btn.dataset.originalText = btn.textContent;
            btn.textContent = text;
        });
    }

    /**
     * Разблокировка кнопки отправки
     */
    static enableSubmitButtons(form) {
        const buttons = form.querySelectorAll('button[type="submit"]');
        buttons.forEach(btn => {
            btn.disabled = false;
            btn.textContent = btn.dataset.originalText || 'Отправить заявку';
        });
    }

    /**
     * Закрытие модального окна
     */
    static closeModal(form) {
        const modal = form.closest('.win_white');
        if (modal) {
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        const overlay = document.querySelector('.winoverlay');
        if (overlay) {
            overlay.classList.remove('visible');
        }
    }
}

/* ============================================================
   ФОРМА ОБРАБОТЧИК
============================================================ */
class FormHandler {
    /**
     * Сбор данных формы
     */
    static collectFormData(form) {
        const formData = new FormData(form);
        const data = {};

        for (const [key, value] of formData.entries()) {
            data[key] = sanitizeString(value);
        }

        // Объединяем части телефона
        if (data.ft && data.code && data.phone) {
            data.phone = sanitizePhone(data.ft + data.code + data.phone);
            delete data.ft;
            delete data.code;
        }

        // Добавляем метаданные
        data.template = form.dataset.template || 'default';
        data.subject = form.dataset.subject || 'Заявка с сайта';
        data.date = new Date().toLocaleDateString('ru-RU', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });

        return data;
    }

    /**
     * Валидация формы
     */
    static validateForm(form, data) {
        logger.debug('Validating form', { template: data.template });

        // Валидация имени
        if (data.name) {
            const nameResult = Validator.validateName(data.name);
            if (!nameResult.valid) {
                UIHelper.highlightField(form.querySelector('[name="name"]'), nameResult.error);
                throw new Error(nameResult.error);
            }
            data.name = nameResult.value;
        }

        // Валидация email
        if (data.email) {
            const emailResult = Validator.validateEmail(data.email);
            if (!emailResult.valid) {
                UIHelper.highlightField(form.querySelector('[name="email"]'), emailResult.error);
                throw new Error(emailResult.error);
            }
            data.email = emailResult.value;
        }

        // Валидация телефона
        if (data.phone) {
            const phoneResult = Validator.validatePhone(data.phone);
            if (!phoneResult.valid) {
                UIHelper.highlightField(form.querySelector('[name="phone"]'), phoneResult.error);
                throw new Error(phoneResult.error);
            }
            data.phone = phoneResult.value;
        }

        // Валидация согласия
        const agreement = form.querySelector('input[name="agreement"]');
        if (agreement) {
            const agreementResult = Validator.validateAgreement(agreement.checked);
            if (!agreementResult.valid) {
                UIHelper.highlightAgreement(agreement);
                throw new Error(agreementResult.error);
            }
        }

        logger.debug('Form validated successfully');
        return data;
    }

    /**
     * Обработка отправки формы
     */
    static async handleSubmit(form, e) {
        e.preventDefault();

        const requestId = generateRequestId();
        const startTime = performance.now();

        try {
            logger.info('Form submission started', { requestId });

            // Сбор данных
            let data = this.collectFormData(form);

            // Валидация
            data = this.validateForm(form, data);

            // Блокировка кнопки
            UIHelper.disableSubmitButtons(form);

            // Отправка на сервер
            const apiStart = performance.now();
            await ApiClient.sendFeedback(data, requestId);
            const apiDuration = performance.now() - apiStart;

            // Отправка в Calltouch (асинхронно, не ждем результата)
            ApiClient.sendCalltouch(data, requestId).catch(() => {});

            // Сброс формы
            form.reset();

            // Закрытие модального окна
            UIHelper.closeModal(form);

            // Показываем успех
            toast.success('Вы успешно отправили форму!');

            const totalDuration = performance.now() - startTime;

            logger.info('Form submission successful', {
                requestId,
                totalDuration: formatDuration(totalDuration),
                apiDuration: formatDuration(apiDuration)
            });

        } catch (error) {
            const duration = performance.now() - startTime;

            logger.error('Form submission failed', {
                requestId,
                error: error.message,
                duration: formatDuration(duration)
            });

            // Показываем ошибку пользователю
            if (error.message.includes('timeout')) {
                toast.error('Превышено время ожидания. Проверьте соединение и попробуйте снова.');
            } else if (error.message.includes('HTTP')) {
                toast.error('Ошибка сервера. Попробуйте позже.');
            } else {
                toast.error(error.message || 'Ошибка отправки формы. Попробуйте позже.');
            }

        } finally {
            UIHelper.enableSubmitButtons(form);
        }
    }
}

/* ============================================================
   МОДАЛЬНАЯ СИСТЕМА
============================================================ */
class ModalSystem {
    constructor() {
        this.overlay = document.querySelector('.winoverlay');
        if (!this.overlay) return;

        this.modals = this.overlay.querySelectorAll('.win_white');
        this.buttons = document.querySelectorAll(
            '.callme, .request, button.getoffer, .btn.buy-kredit, .fastorder, ' +
            '.callcall, .callme.banner-coffee-link, .calculator-btn'
        );

        this.init();
    }

    init() {
        logger.debug('Initializing modal system', {
            modalsCount: this.modals.length,
            buttonsCount: this.buttons.length
        });

        // Предотвращаем закрытие при клике внутри модалки
        this.modals.forEach(modal => {
            modal.addEventListener('click', (e) => e.stopPropagation());
        });

        // Открытие модалок
        this.buttons.forEach(btn => {
            btn.addEventListener('click', () => this.openModal(btn));
        });

        // Закрытие модалок
        this.overlay.querySelectorAll('.open_close').forEach(closeBtn => {
            closeBtn.addEventListener('click', () => this.closeAll());
        });

        // Закрытие по клику на overlay
        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) {
                this.closeAll();
            }
        });

        // Закрытие по ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.overlay.classList.contains('visible')) {
                this.closeAll();
            }
        });
    }

    hideAll() {
        this.modals.forEach(modal => {
            modal.style.display = 'none';
            modal.style.opacity = '0';
        });
    }

    openModal(btn) {
        const target = btn.dataset.target;
        const subject = btn.dataset.subject;

        logger.debug('Opening modal', { target, subject });

        this.hideAll();

        const modal = this.overlay.querySelector(target);
        if (modal) {
            modal.style.display = 'block';
            
            if (subject) {
                const subjectEl = modal.querySelector('.zvonok');
                if (subjectEl) {
                    subjectEl.textContent = subject;
                }
            }

            requestAnimationFrame(() => {
                modal.style.opacity = '1';
            });
        }

        this.overlay.classList.add('visible');
    }

    closeAll() {
        logger.debug('Closing all modals');
        this.overlay.classList.remove('visible');
        this.hideAll();
    }
}

/* ============================================================
   ИНИЦИАЛИЗАЦИЯ
============================================================ */
document.addEventListener('DOMContentLoaded', () => {
    logger.info('Initializing feedback forms system');

    try {
        // Инициализация модальной системы
        new ModalSystem();

        // Получаем все формы
        const forms = document.querySelectorAll(
            '.win_white form, .feedback-form, #winProduct, ' +
            '.leasing-form, #feedback, #catchform2'
        );

        logger.info('Forms found', { count: forms.length });

        // Обрабатываем каждую форму
        forms.forEach((form, index) => {
            // Открываем ссылки в новом окне
            form.querySelectorAll('a').forEach(a => {
                a.setAttribute('target', '_blank');
                a.setAttribute('rel', 'noopener noreferrer');
            });

            // Добавляем обработчик отправки
            form.addEventListener('submit', (e) => {
                FormHandler.handleSubmit(form, e);
            });

            logger.debug('Form initialized', {
                index,
                template: form.dataset.template,
                id: form.id
            });
        });

        logger.info('Feedback forms system initialized successfully');

    } catch (error) {
        logger.error('Failed to initialize feedback forms system', {
            error: error.message,
            stack: error.stack,
            critical: true
        });
    }
});

/* ============================================================
   ЭКСПОРТ ДЛЯ ИСПОЛЬЗОВАНИЯ ИЗ ВНЕШНЕГО КОДА
============================================================ */
window.FeedbackForms = {
    toast,
    logger,
    ApiClient,
    Validator,
    UIHelper
};
