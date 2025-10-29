'use strict';

// Общие элементы
const overlay = document.querySelector('.winoverlay');

// Сообщения
const showSuccessMessage = () => {
    Toastify({
        text: 'Вы успешно отправили форму!',
        style: {
            background: '#4a259b',
            right: '45vw',
            padding: '20px'
        }
    }).showToast();
};

const showErrorMessage = (msg) => {
    Toastify({
        text: msg,
        style: {
            right: '35vw',
            padding: '20px',
            background: 'red'
        }
    }).showToast();
};

// Подсветка label рядом с чекбоксом (привязана к форме)
const highlightAgreement = (agreementCheckbox) => {
    if (!agreementCheckbox) return;

    // Добавляем класс на сам чекбокс (label подсветится через CSS-селектор + .prv)
    agreementCheckbox.classList.add('checkbox-error');

    // Убираем подсветку через 1.5 секунды
    setTimeout(() => {
        agreementCheckbox.classList.remove('checkbox-error');
    }, 1500);
};


// Сбор данных формы
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

// Отправка данных
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

// Отправка в Calltouch
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

// Привязка логики ко всем формам
const forms = document.querySelectorAll('#winMain, #request, #fast, #winProduct');

forms.forEach(form => {

    console.log(form.querySelectorAll('a'))

    form.querySelectorAll('a').forEach((link) => {
        link.setAttribute('target', '_blank');
        link.setAttribute('rel','noopener noreferrer');
    })

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const data = getFeedback(form);
        const agreement = form.querySelector('input[name="agreement"]');
        if (agreement) agreement.required = true;

        if (agreement && !agreement.checked) {
            highlightAgreement(agreement); // теперь подсветка ищет label внутри текущей формы
            showErrorMessage('Поставьте галочку согласия на обработку персональных данных');
            return;
        }

        sendFeedback(form, data);
        sendCalltouchData(data);
    });
});


