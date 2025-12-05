'use strict';

document.addEventListener('DOMContentLoaded', () => {

    console.log('checkout loaded');

    const form = document.querySelector('#checkout');
    const ct_site_id = 49728;
    const buttonLabel = 'Дальше';
    const emailInput = form.querySelector('#email');


    if (!form) {
        console.error('Checkout form not found');
        return;
    }

    const submitButton = form.querySelector('.btn.btn-submit-checkout');
    if (!submitButton) {
        console.error('Submit button not found');
        return;
    }

    let isSubmitting = false;

    // ---------------------------
    // SAFE JSON PARSER
    // ---------------------------
    const safeJson = async (res) => {
        try {
            return await res.json();
        } catch {
            console.warn('Response is not valid JSON');
            return {};
        }
    };

    // ---------------------------
    // RIPPLE EFFECT
    // ---------------------------
    const buttons = document.querySelectorAll('.btn.btn-submit-checkout');

    const createRipple = (button, e) => {
        if (isSubmitting) return; // не рисовать ripple при отправке

        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;

        const ripple = document.createElement('span');
        ripple.className = 'ripple';
        ripple.style.width = size + 'px';
        ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.style.position = 'absolute';
        ripple.style.borderRadius = '50%';
        ripple.style.backgroundColor = 'rgba(255,255,255,0.4)';
        ripple.style.transform = 'scale(0)';
        ripple.style.opacity = '1';
        ripple.style.pointerEvents = 'none';
        ripple.style.transition = 'transform 0.6s ease-out, opacity 0.6s ease-out';

        button.appendChild(ripple);

        requestAnimationFrame(() => {
            ripple.style.transform = 'scale(2.5)';
            ripple.style.opacity = '0';
        });

        setTimeout(() => ripple.remove(), 600);
    };

    buttons.forEach(btn => {
        btn.style.position = 'relative'; // чтобы ripple позиционировался правильно
        btn.addEventListener('click', (e) => createRipple(btn, e));
    });

    // ---------------------------
    // SUBMIT HANDLER
    // ---------------------------
    form.addEventListener('submit', handleSubmit);

// -------------------------
// Основной обработчик
// -------------------------
    async function handleSubmit(event) {
        event.preventDefault();

        if (isSubmitting) {
            console.warn('Повторная отправка предотвращена');
            return;
        }

        const formEl = event.target;
        const submitBtn = submitButton;

        const restoreButtonState = lockButton(submitBtn);

        try {
            const fd = new FormData(formEl);
            const data = normalizeForm(fd);

            if (!validateAgreement(data.agreement)) {
                showError('Для отправки заявки Вам необходимо дать согласие на обработку персональных данных в соответствии с политикой конфиденциальности ООО "Вендпром"');
                return;
            }

            if (!validateRequiredFields(data)) {
                showError('Заполните все поля');
                return;
            }

            const [confirmRes, callTouchRes] = await sendParallelRequests(fd, data);

            if (confirmRes.ok && callTouchRes.ok) {
                showSuccess('Заявка отправлена!');
            }

            if (confirmRes.json?.redirect) {
                location.assign(confirmRes.json.redirect);
            }

        } catch (err) {
            console.error('Checkout error:', err);
            showError('Ошибка при отправке формы');
        } finally {
            restoreButtonState();
        }
    }

// -------------------------
// ЛОГИКА ОТПРАВКИ
// -------------------------

    async function sendParallelRequests(fd, data) {
        const callTouchPayload = new URLSearchParams({
            fio: data.firstname,
            phoneNumber: data.telephone,
            subject: 'Заявка из корзины',
            sessionId: window.call_value || '',
            requestUrl: location.href
        });

        const confirmReq = fetch('index.php?route=checkout/confirm', {
            method: 'POST',
            body: fd
        });

        const callTouchReq = fetch(
            `https://api.calltouch.ru/calls-service/RestAPI/requests/${ct_site_id}/register/`,
            {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: callTouchPayload.toString()
            }
        );

        const results = await Promise.allSettled([confirmReq, callTouchReq]);

        const confirmRes = await parseSafe(results[0]);
        const callTouchRes = await parseSafe(results[1]);

        return [confirmRes, callTouchRes];
    }

// -------------------------
// ПАРСИНГ JSON
// -------------------------
    async function parseSafe(result) {
        if (result.status !== 'fulfilled') {
            return { ok: false, json: null };
        }

        const resp = result.value;
        let json = null;

        try {
            json = await resp.clone().json();
        } catch (_) {}

        return { ok: resp.ok, json };
    }

// -------------------------
// ОБРАБОТКА ДАННЫХ ФОРМЫ
// -------------------------

    function normalizeForm(fd) {
        const data = Object.fromEntries(fd.entries());
        data.firstname = (data.firstname || '').trim();
        data.telephone = (data.telephone || '').trim();
        data.agreement = data.agreement === 'on';
        return data;
    }

    function validateAgreement(agree) {
        return agree === true;
    }

    function validateRequiredFields(data) {
        return Boolean(data.firstname && data.telephone);
    }

// -------------------------
// КНОПКА
// -------------------------

    function lockButton(btn) {
        const initialText = btn.textContent;

        isSubmitting = true;
        btn.disabled = true;
        btn.textContent = 'Отправка...';

        return () => {
            btn.disabled = false;
            btn.textContent = initialText;
            isSubmitting = false;
        };
    }

// -------------------------
// UI Уведомления
// -------------------------
    function showError(text) {
        Toastify({
            text,
            duration: 3000,
            gravity: "top",
            position: "center",
            style: {background:"#D9534F"}
        }).showToast();
    }

    function showSuccess(text) {
        Toastify({
            text,
            duration: 3000,
            gravity: "top",
            position: "center",
            style: {background:"#4A259B"}
        }).showToast();
    }

    emailInput.addEventListener('input', () => {
        console.log('emailInput');
        const val = emailInput.value.trim();
        const valid = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(val);
        emailInput.setCustomValidity(valid ? '' : 'Введите корректный email');
    });
});
