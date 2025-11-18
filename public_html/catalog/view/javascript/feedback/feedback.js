'use strict';

// === Общие элементы ===
const overlay = document.querySelector('.winoverlay');

// === НАСТРОЙКИ TOASTIFY ===
const TOAST_STACK_LIMIT = 5;
const TOAST_DURATION = 4000;
let activeToasts = [];

// === Проверка мобильного устройства ===
const isMobile = () => window.matchMedia('(max-width: 768px)').matches;

// === Универсальный показ уведомлений ===
const showToast = (msg, background = '#4a259b') => {
    if (activeToasts.length >= TOAST_STACK_LIMIT) {
        const oldest = activeToasts.shift();
        oldest?.remove();
    }

    const baseY = isMobile() ? 10 : 20;
    const position = isMobile() ? 'center' : 'right';
    const gravity = isMobile() ? 'bottom' : 'top';

    const toast = Toastify({
        text: msg,
        duration: TOAST_DURATION,
        gravity,
        position,
        stopOnFocus: true,
        offset: { x: isMobile() ? 0 : 20, y: baseY + activeToasts.length * 70 },
        style: {
            background,
            padding: isMobile() ? '14px 18px' : '18px 22px',
            borderRadius: '10px',
            fontSize: isMobile() ? '14px' : '15px',
            maxWidth: isMobile() ? '90%' : '340px',
            width: 'auto',
            textAlign: 'center',
            margin: isMobile() ? '0 auto' : 'initial',
            boxShadow: '0 4px 10px rgba(0,0,0,0.15)',
        },
        callback: () => {
            activeToasts = activeToasts.filter(t => t !== toast.el);
        }
    });

    toast.showToast();
    activeToasts.push(toast.el);
};

const showSuccessMessage = () => showToast('Вы успешно отправили форму!');
const showErrorMessage = msg => showToast(msg, 'red');

// === Подсветка чекбокса ===
const highlightAgreement = (checkbox) => {
    if (!checkbox) return;
    checkbox.classList.add('checkbox-error');
    if (navigator.vibrate) navigator.vibrate(100); // лёгкая вибрация
    setTimeout(() => checkbox.classList.remove('checkbox-error'), 1500);
    checkbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
};

// === Сбор данных формы ===
const getFeedback = (form) => {
    const formData = Object.fromEntries(new FormData(form));
    const today = new Date().toLocaleDateString('ru-RU');

    formData.phone = formData.ft + formData.code + formData.phone;
    delete formData.ft;
    delete formData.code;

    formData.template = form.dataset.template;
    formData.date = today;
    formData.subject = form.dataset.subject;

    return formData;
};

// === Отправка данных ===
const sendFeedback = async (form, data) => {
    const submitButtons = form.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(btn => {
        btn.disabled = true;
        btn.textContent = 'Отправка...';
    });

    console.log(submitButtons);

    try {
        const res = await fetch('index.php?route=common/feedback', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });

        if (!res.ok) {
            showErrorMessage(`Ошибка ${res.status}: не удалось обработать заявку`);
            return;
        }

        form.reset();

        const modal = form.closest('.win_white');
        if (modal) {
            modal.style.transition = 'opacity 0.3s ease';
            modal.style.opacity = '0';
            setTimeout(() => modal.style.display = 'none', 300);
        }

        const overlay = document.querySelector('.overlay'); // <- добавить поиск явно
        if (overlay) {
            overlay.style.transition = 'opacity 0.3s ease';
            overlay.style.opacity = '0';
            setTimeout(() => overlay.style.display = 'none', 300);
        }

        showSuccessMessage();

    } catch (err) {
        console.error('Ошибка отправки формы:', err);
        showErrorMessage('Ошибка сети. Повторите попытку позже.');
    } finally {
        submitButtons.forEach(btn => {
            btn.disabled = false
            btn.textContent = 'Отправить заявку';
        });
        location.reload()
    }
};


// === Отправка в Calltouch ===
const sendCalltouchData = (data) => {
    const ct_site_id = '49728';
    jQuery.ajax({
        url: `https://api.calltouch.ru/calls-service/RestAPI/requests/${ct_site_id}/register/`,
        dataType: 'json',
        type: 'POST',
        data: {
            fio: data.name,
            phoneNumber: data.phone,
            email: data.email,
            subject: data.subject || 'Заявка с сайта vend-shop.com',
            sessionId: window.call_value,
            requestUrl: location.href,
            comment: data.note
        }
    });
};

// === Привязка логики к формам ===
const forms = document.querySelectorAll('#winMain, #request, #fast, #winProduct, #feedback');

forms.forEach(form => {
    form.querySelectorAll('a').forEach(link => {
        link.setAttribute('target', '_blank');
        link.setAttribute('rel', 'noopener noreferrer');
    });

    form.addEventListener('submit', e => {
        e.preventDefault();

        const data = getFeedback(form);
        const agreement = form.querySelector('input[name="agreement"]');

        if (agreement && !agreement.checked) {
            highlightAgreement(agreement);
            showErrorMessage('Поставьте галочку согласия на обработку персональных данных');
            return;
        }

        sendFeedback(form, data);
        sendCalltouchData(data);
    });
});

// === Улучшение UX для мобильных устройств ===
window.addEventListener('resize', () => {
    activeToasts.forEach(t => t.remove());
    activeToasts = [];
});
