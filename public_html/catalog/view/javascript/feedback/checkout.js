'use strict';

/**
 * Checkout Form Handler
 * Обработчик формы оформления заказа с валидацией, логированием и обработкой ошибок
 */

// ============================================================================
// CONFIGURATION
// ============================================================================

const CONFIG = {
    callTouch: {
        siteId: 49728,
        apiUrl: 'https://api.calltouch.ru/calls-service/RestAPI/requests',
        timeout: 10000
    },

    api: {
        confirmUrl: 'index.php?route=checkout/confirm',
        timeout: 15000,
        retryAttempts: 2,
        retryDelay: 1000
    },

    ui: {
        buttonLabels: {
            default: 'Дальше',
            submitting: 'Отправка...',
            success: 'Готово!'
        },

        toast: {
            duration: 3000,
            gravity: 'top',
            position: 'center',
            styles: {
                error: { background: '#D9534F' },
                success: { background: '#4A259B' },
                warning: { background: '#F0AD4E' }
            }
        },

        ripple: {
            duration: 600,
            scale: 2.5,
            color: 'rgba(255, 255, 255, 0.4)'
        }
    },

    validation: {
        emailRegex: /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/,
        phoneRegex: /^[\d\s\-\+\(\)]+$/,
        minPhoneLength: 10,
        maxPhoneLength: 18
    },

    logging: {
        enabled: true,
        level: 'info' // 'debug', 'info', 'warn', 'error'
    }
};

// ============================================================================
// LOGGING UTILITY
// ============================================================================

class Logger {
    constructor(config) {
        this.enabled = config.enabled;
        this.level = config.level;
        this.levels = { debug: 0, info: 1, warn: 2, error: 3 };
    }

    _shouldLog(level) {
        return this.enabled && this.levels[level] >= this.levels[this.level];
    }

    _formatMessage(level, message, context = {}) {
        const timestamp = new Date().toISOString();
        const contextStr = Object.keys(context).length > 0
            ? ` | ${JSON.stringify(context)}`
            : '';
        return `[${timestamp}] [${level.toUpperCase()}] ${message}${contextStr}`;
    }

    debug(message, context) {
        if (this._shouldLog('debug')) {
            console.log(this._formatMessage('debug', message, context));
        }
    }

    info(message, context) {
        if (this._shouldLog('info')) {
            console.info(this._formatMessage('info', message, context));
        }
    }

    warn(message, context) {
        if (this._shouldLog('warn')) {
            console.warn(this._formatMessage('warn', message, context));
        }
    }

    error(message, context) {
        if (this._shouldLog('error')) {
            console.error(this._formatMessage('error', message, context));
        }
    }
}

const logger = new Logger(CONFIG.logging);

// ============================================================================
// VALIDATION UTILITIES
// ============================================================================

class Validator {
    static sanitizeString(str) {
        if (typeof str !== 'string') return '';
        return str.trim().replace(/[<>]/g, '');
    }

    static sanitizePhone(phone) {
        if (typeof phone !== 'string') return '';
        return phone.trim().replace(/[^\d\s\-\+\(\)]/g, '');
    }

    static validateEmail(email) {
        const sanitized = this.sanitizeString(email);

        if (!sanitized) {
            return { valid: false, error: 'Email не может быть пустым' };
        }

        if (sanitized.length > 254) {
            return { valid: false, error: 'Email слишком длинный' };
        }

        if (!CONFIG.validation.emailRegex.test(sanitized)) {
            return { valid: false, error: 'Введите корректный email' };
        }

        return { valid: true, value: sanitized };
    }

    static validatePhone(phone) {
        const sanitized = this.sanitizePhone(phone);
        const digitsOnly = sanitized.replace(/\D/g, '');

        if (!sanitized) {
            return { valid: false, error: 'Телефон не может быть пустым' };
        }

        if (!CONFIG.validation.phoneRegex.test(sanitized)) {
            return { valid: false, error: 'Телефон содержит недопустимые символы' };
        }

        if (digitsOnly.length < CONFIG.validation.minPhoneLength) {
            return { valid: false, error: 'Телефон слишком короткий' };
        }

        if (digitsOnly.length > CONFIG.validation.maxPhoneLength) {
            return { valid: false, error: 'Телефон слишком длинный' };
        }

        return { valid: true, value: sanitized };
    }

    static validateName(name) {
        const sanitized = this.sanitizeString(name);

        if (!sanitized) {
            return { valid: false, error: 'Имя не может быть пустым' };
        }

        if (sanitized.length < 2) {
            return { valid: false, error: 'Имя слишком короткое' };
        }

        if (sanitized.length > 100) {
            return { valid: false, error: 'Имя слишком длинное' };
        }

        return { valid: true, value: sanitized };
    }

    static validateAgreement(agreement) {
        if (agreement !== true) {
            return {
                valid: false,
                error: 'Для отправки заявки Вам необходимо дать согласие на обработку персональных данных в соответствии с политикой конфиденциальности ООО "Вендпром"'
            };
        }

        return { valid: true };
    }
}

// ============================================================================
// API UTILITIES
// ============================================================================

class ApiClient {
    static async fetchWithTimeout(url, options = {}, timeout = 15000) {
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
                throw new Error('Request timeout');
            }
            throw error;
        }
    }

    static async fetchWithRetry(url, options = {}, retries = 2, delay = 1000) {
        let lastError;

        for (let attempt = 0; attempt <= retries; attempt++) {
            try {
                logger.debug(`API Request attempt ${attempt + 1}/${retries + 1}`, { url });

                const response = await this.fetchWithTimeout(
                    url,
                    options,
                    CONFIG.api.timeout
                );

                logger.info('API Request successful', {
                    url,
                    status: response.status,
                    attempt: attempt + 1
                });

                return response;

            } catch (error) {
                lastError = error;
                logger.warn(`API Request failed, attempt ${attempt + 1}/${retries + 1}`, {
                    url,
                    error: error.message
                });

                if (attempt < retries) {
                    await this.sleep(delay * (attempt + 1)); // Exponential backoff
                }
            }
        }

        logger.error('API Request failed after all retries', {
            url,
            error: lastError.message
        });

        throw lastError;
    }

    static sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    static async parseJsonSafe(response) {
        try {
            return await response.clone().json();
        } catch (error) {
            logger.warn('Failed to parse JSON response', {
                status: response.status,
                error: error.message
            });
            return null;
        }
    }
}

// ============================================================================
// UI UTILITIES
// ============================================================================

class UIHelper {
    static showToast(text, type = 'info') {
        const style = CONFIG.ui.toast.styles[type] || CONFIG.ui.toast.styles.success;

        if (typeof Toastify !== 'undefined') {
            Toastify({
                text,
                duration: CONFIG.ui.toast.duration,
                gravity: CONFIG.ui.toast.gravity,
                position: CONFIG.ui.toast.position,
                style
            }).showToast();
        } else {
            // Fallback если Toastify не загружен
            alert(text);
        }

        logger.info('Toast shown', { text, type });
    }

    static showError(text) {
        this.showToast(text, 'error');
    }

    static showSuccess(text) {
        this.showToast(text, 'success');
    }

    static showWarning(text) {
        this.showToast(text, 'warning');
    }

    static createRipple(button, event, isSubmitting) {
        if (isSubmitting) return;

        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;

        const ripple = document.createElement('span');
        ripple.className = 'ripple';

        Object.assign(ripple.style, {
            width: `${size}px`,
            height: `${size}px`,
            left: `${x}px`,
            top: `${y}px`,
            position: 'absolute',
            borderRadius: '50%',
            backgroundColor: CONFIG.ui.ripple.color,
            transform: 'scale(0)',
            opacity: '1',
            pointerEvents: 'none',
            transition: `transform ${CONFIG.ui.ripple.duration}ms ease-out, opacity ${CONFIG.ui.ripple.duration}ms ease-out`
        });

        button.appendChild(ripple);

        requestAnimationFrame(() => {
            ripple.style.transform = `scale(${CONFIG.ui.ripple.scale})`;
            ripple.style.opacity = '0';
        });

        setTimeout(() => ripple.remove(), CONFIG.ui.ripple.duration);
    }

    static lockButton(button) {
        const initialText = button.textContent;

        button.disabled = true;
        button.textContent = CONFIG.ui.buttonLabels.submitting;

        logger.debug('Button locked', { initialText });

        return () => {
            button.disabled = false;
            button.textContent = initialText;
            logger.debug('Button unlocked');
        };
    }
}

// ============================================================================
// CHECKOUT HANDLER
// ============================================================================

class CheckoutHandler {
    constructor(form) {
        this.form = form;
        this.submitButton = form.querySelector('.btn.btn-submit-checkout');
        this.emailInput = form.querySelector('#email');
        this.isSubmitting = false;

        this.init();
    }

    init() {
        if (!this.form || !this.submitButton) {
            logger.error('Required form elements not found', {
                hasForm: !!this.form,
                hasButton: !!this.submitButton
            });
            return;
        }

        logger.info('Checkout handler initialized');

        this.setupEventListeners();
        this.setupRippleEffect();
    }

    setupEventListeners() {
        this.form.addEventListener('submit', (e) => this.handleSubmit(e));

        if (this.emailInput) {
            this.emailInput.addEventListener('input', () => this.validateEmailInput());
            this.emailInput.addEventListener('blur', () => this.validateEmailInput());
        }

        logger.debug('Event listeners attached');
    }

    setupRippleEffect() {
        const buttons = this.form.querySelectorAll('.btn.btn-submit-checkout');

        buttons.forEach(btn => {
            btn.style.position = 'relative';
            btn.style.overflow = 'hidden';

            btn.addEventListener('click', (e) => {
                UIHelper.createRipple(btn, e, this.isSubmitting);
            });
        });

        logger.debug('Ripple effect setup complete', { buttonCount: buttons.length });
    }

    validateEmailInput() {
        if (!this.emailInput) return;

        const value = this.emailInput.value.trim();

        if (!value) {
            this.emailInput.setCustomValidity('');
            return;
        }

        const validation = Validator.validateEmail(value);

        if (!validation.valid) {
            this.emailInput.setCustomValidity(validation.error);
            logger.debug('Email validation failed', { error: validation.error });
        } else {
            this.emailInput.setCustomValidity('');
            logger.debug('Email validation passed');
        }
    }

    normalizeFormData(formData) {
        const data = Object.fromEntries(formData.entries());

        // Sanitize и normalize данные
        data.firstname = Validator.sanitizeString(data.firstname || '');
        data.telephone = Validator.sanitizePhone(data.telephone || '');
        data.email = Validator.sanitizeString(data.email || '');
        data.comment = Validator.sanitizeString(data.comment || '');
        data.agreement = data.agreement === 'on';

        logger.debug('Form data normalized', {
            hasName: !!data.firstname,
            hasPhone: !!data.telephone,
            hasEmail: !!data.email,
            hasAgreement: data.agreement
        });

        return data;
    }

    validateFormData(data) {
        const errors = [];

        // Валидация согласия
        const agreementValidation = Validator.validateAgreement(data.agreement);
        if (!agreementValidation.valid) {
            errors.push(agreementValidation.error);
        }

        // Валидация имени
        const nameValidation = Validator.validateName(data.firstname);
        if (!nameValidation.valid) {
            errors.push(`Имя: ${nameValidation.error}`);
        }

        // Валидация телефона
        const phoneValidation = Validator.validatePhone(data.telephone);
        if (!phoneValidation.valid) {
            errors.push(`Телефон: ${phoneValidation.error}`);
        }

        // Валидация email (если заполнен)
        if (data.email) {
            const emailValidation = Validator.validateEmail(data.email);
            if (!emailValidation.valid) {
                errors.push(`Email: ${emailValidation.error}`);
            }
        }

        if (errors.length > 0) {
            logger.warn('Form validation failed', { errors });
            return { valid: false, errors };
        }

        logger.info('Form validation passed');
        return { valid: true };
    }

    async sendConfirmRequest(formData) {
        logger.info('Sending confirm request');

        try {
            const response = await ApiClient.fetchWithRetry(
                CONFIG.api.confirmUrl,
                {
                    method: 'POST',
                    body: formData
                },
                CONFIG.api.retryAttempts,
                CONFIG.api.retryDelay
            );

            const json = await ApiClient.parseJsonSafe(response);

            return {
                ok: response.ok,
                status: response.status,
                json
            };

        } catch (error) {
            logger.error('Confirm request failed', {
                error: error.message,
                stack: error.stack
            });

            return {
                ok: false,
                status: 0,
                json: null,
                error: error.message
            };
        }
    }

    async sendCallTouchRequest(data) {
        logger.info('Sending CallTouch request');

        const payload = new URLSearchParams({
            fio: data.firstname,
            phoneNumber: data.telephone,
            email: data.email || '',
            subject: 'Заявка из корзины',
            sessionId: window.call_value || '',
            requestUrl: location.href
        });

        const url = `${CONFIG.callTouch.apiUrl}/${CONFIG.callTouch.siteId}/register/`;

        try {
            const response = await ApiClient.fetchWithTimeout(
                url,
                {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: payload.toString()
                },
                CONFIG.callTouch.timeout
            );

            const json = await ApiClient.parseJsonSafe(response);

            return {
                ok: response.ok,
                status: response.status,
                json
            };

        } catch (error) {
            logger.error('CallTouch request failed', {
                error: error.message,
                stack: error.stack
            });

            // CallTouch не критичен, возвращаем успех
            return {
                ok: true,
                status: 0,
                json: null,
                error: error.message,
                skipped: true
            };
        }
    }

    async sendParallelRequests(formData, data) {
        logger.info('Starting parallel requests');

        const startTime = performance.now();

        const [confirmResult, callTouchResult] = await Promise.allSettled([
            this.sendConfirmRequest(formData),
            this.sendCallTouchRequest(data)
        ]);

        const duration = Math.round(performance.now() - startTime);

        logger.info('Parallel requests completed', {
            duration: `${duration}ms`,
            confirmStatus: confirmResult.status,
            callTouchStatus: callTouchResult.status
        });

        const confirmRes = confirmResult.status === 'fulfilled'
            ? confirmResult.value
            : { ok: false, error: confirmResult.reason?.message };

        const callTouchRes = callTouchResult.status === 'fulfilled'
            ? callTouchResult.value
            : { ok: false, error: callTouchResult.reason?.message };

        return { confirmRes, callTouchRes, duration };
    }

    async handleSubmit(event) {
        event.preventDefault();

        if (this.isSubmitting) {
            logger.warn('Duplicate submit prevented');
            UIHelper.showWarning('Форма уже отправляется...');
            return;
        }

        logger.info('Form submission started');
        const submitStartTime = performance.now();

        this.isSubmitting = true;
        const restoreButton = UIHelper.lockButton(this.submitButton);

        try {
            // Получение и нормализация данных
            const formData = new FormData(this.form);
            const data = this.normalizeFormData(formData);

            // Валидация
            const validation = this.validateFormData(data);
            if (!validation.valid) {
                UIHelper.showError(validation.errors[0]);
                return;
            }

            // Отправка запросов
            const { confirmRes, callTouchRes, duration } = await this.sendParallelRequests(formData, data);

            // Обработка результатов
            if (!confirmRes.ok) {
                logger.error('Confirm request failed', {
                    status: confirmRes.status,
                    error: confirmRes.error
                });
                UIHelper.showError('Ошибка при отправке заказа. Попробуйте еще раз.');
                return;
            }

            // CallTouch не критичен, логируем только если провалился
            if (!callTouchRes.ok && !callTouchRes.skipped) {
                logger.warn('CallTouch request failed (non-critical)', {
                    error: callTouchRes.error
                });
            }

            // Успех
            const totalDuration = Math.round(performance.now() - submitStartTime);

            logger.info('Form submission successful', {
                totalDuration: `${totalDuration}ms`,
                apiDuration: `${duration}ms`
            });

            UIHelper.showSuccess('Заявка успешно отправлена!');

            // Перенаправление если есть
            if (confirmRes.json?.redirect) {
                logger.info('Redirecting', { url: confirmRes.json.redirect });
                setTimeout(() => {
                    location.assign(confirmRes.json.redirect);
                }, 1000);
            }

        } catch (error) {
            logger.error('Unexpected error during form submission', {
                error: error.message,
                stack: error.stack
            });

            UIHelper.showError('Произошла непредвиденная ошибка. Попробуйте еще раз.');

        } finally {
            this.isSubmitting = false;
            restoreButton();
        }
    }
}

// ============================================================================
// INITIALIZATION
// ============================================================================

document.addEventListener('DOMContentLoaded', () => {
    logger.info('DOM Content Loaded');

    try {
        const form = document.querySelector('#checkout');

        if (!form) {
            logger.error('Checkout form not found');
            return;
        }

        new CheckoutHandler(form);
        logger.info('Checkout initialization complete');

    } catch (error) {
        logger.error('Failed to initialize checkout', {
            error: error.message,
            stack: error.stack
        });
    }
});