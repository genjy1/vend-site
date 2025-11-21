'use strict';

document.addEventListener('DOMContentLoaded', () => {

    console.log('checkout loaded');

    const form = document.querySelector('#checkout');
    const ct_site_id = 49728;
    const buttonLabel = 'Дальше';

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
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (isSubmitting) {
            console.warn('Submit prevented: form already submitting');
            return;
        }

        isSubmitting = true;
        submitButton.disabled = true; // блокируем кнопку
        submitButton.textContent = 'Отправка...';

        try {
            const fd = new FormData(e.target);
            const data = Object.fromEntries(fd.entries());

            const firstname = (data.firstname || '').trim();
            const phone = (data.telephone || '').trim();

            if (!firstname || !phone) {
                Toastify({
                    text: "Заполните все поля",
                    duration: 2500,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#D9534F"
                }).showToast();
                return;
            }

            const callTouchPayload = new URLSearchParams({
                fio: firstname,
                phoneNumber: phone,
                subject: 'Заявка из корзины',
                sessionId: window.call_value || '',
                requestUrl: location.href
            });

            const confirmPromise = fetch('index.php?route=checkout/confirm', {
                method: 'POST',
                body: fd
            });

            const callTouchPromise = fetch(
                `https://api.calltouch.ru/calls-service/RestAPI/requests/${ct_site_id}/register/`,
                {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: callTouchPayload.toString()
                }
            );

            const [confirmRes, callTouchRes] = await Promise.all([confirmPromise, callTouchPromise]);

            if (!confirmRes.ok) {
                console.error('Checkout confirm request failed:', confirmRes.status);
                return;
            }

            if (!callTouchRes.ok) {
                console.error('CallTouch request failed:', callTouchRes.status);
                return;
            }

            const result = await safeJson(confirmRes);
            const callTouchResult = await safeJson(callTouchRes);

            const okConfirm = result?.status === 200 || result?.success === true;
            const okCt = callTouchResult?.status === 200 || callTouchResult?.success === true;

            if (okConfirm && okCt) {
                Toastify({
                    text: "Заявка отправлена!",
                    duration: 3000,
                    gravity: "top",
                    position: "center",
                    backgroundColor: "#4A259B"
                }).showToast();
            }

            console.log('confirm result:', result);

            if (result.redirect) {
                location.assign(result.redirect);
            }

        } catch (err) {
            console.error('Checkout error:', err);
        } finally {
            isSubmitting = false;
            submitButton.disabled = false; // разблокируем кнопку
            submitButton.textContent = buttonLabel;
        }
    });

});
