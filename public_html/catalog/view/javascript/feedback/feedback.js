'use strict';

// === Общие элементы ===
const overlay = document.querySelector('.winoverlay');

// === НАСТРОЙКИ TOASTIFY ===
const TOAST_STACK_LIMIT = 5; // максимум одновременных уведомлений
const TOAST_DURATION = 4000; // миллисекунд

let activeToasts = []; // активные уведомления

// === Универсальный показ уведомлений ===
const showToast = (msg, background = '#4a259b') => {
    // Удаляем старые тосты, если превышен лимит
    if (activeToasts.length >= TOAST_STACK_LIMIT) {
        const oldest = activeToasts.shift();
        oldest?.remove();
    }

    const toast = Toastify({
        text: msg,
        duration: TOAST_DURATION,
        gravity: 'top',
        position: 'right',
        stopOnFocus: true,
        offset: { x: 20, y: 20 + activeToasts.length * 70 },
        style: {
            background,
            padding: '18px 22px',
            borderRadius: '8px',
            fontSize: '15px',
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
    setTimeout(() => checkbox.classList.remove('checkbox-error'), 1500);
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
    try {
        const res = await fetch('index.php?route=common/feedback', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: { "Content-Type": "application/json" }
        });

        if (res.ok) {
            form.reset();
            const modal = form.closest('.win_white');
            if (modal) modal.style.display = 'none';
            if (overlay) overlay.style.display = 'none';
            showSuccessMessage();
        } else {
            showErrorMessage(`Ошибка ${res.status}: не удалось обработать заявку`);
        }
    } catch (err) {
        console.error('Ошибка отправки формы:', err);
        showErrorMessage('Ошибка сети. Повторите попытку позже.');
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
const forms = document.querySelectorAll('#winMain, #request, #fast, #winProduct');

forms.forEach(form => {
    // Все ссылки в форме открываются в новой вкладке
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
