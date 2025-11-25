'use strict';

/* ============================================================
   TOASTIFY
============================================================ */
const TOAST_STACK_LIMIT = 5;
const TOAST_DURATION = 4000;
let activeToasts = [];

const isMobile = () => window.matchMedia('(max-width: 768px)').matches;

const showToast = (msg, background = '#4a259b') => {
    if (activeToasts.length >= TOAST_STACK_LIMIT) {
        const oldest = activeToasts.shift();
        oldest?.remove();
    }

    const baseY = isMobile() ? 10 : 20;

    const toast = Toastify({
        text: msg,
        duration: TOAST_DURATION,
        gravity: isMobile() ? 'bottom' : 'top',
        position: isMobile() ? 'center' : 'right',
        stopOnFocus: true,
        offset: { x: isMobile() ? 0 : 20, y: baseY + activeToasts.length * 70 },
        style: {
            background,
            padding: isMobile() ? '14px 18px' : '18px 22px',
            borderRadius: '10px',
            fontSize: isMobile() ? '14px' : '15px',
            maxWidth: isMobile() ? '90%' : '340px',
            textAlign: 'center',
            boxShadow: '0 4px 10px rgba(0,0,0,0.15)',
            color: 'white',
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


/* ============================================================
   ПОДСВЕТКА ЧЕКБОКСА СОГЛАСИЯ
============================================================ */
const highlightAgreement = checkbox => {
    if (!checkbox) return;
    checkbox.classList.add('checkbox-error');
    if (navigator.vibrate) navigator.vibrate(100);

    setTimeout(() => checkbox.classList.remove('checkbox-error'), 1500);
    checkbox.scrollIntoView({ behavior: 'smooth', block: 'center' });
};


/* ============================================================
   МОДАЛЬНАЯ СИСТЕМА
============================================================ */
document.addEventListener('DOMContentLoaded', () => {

    const overlay = document.querySelector('.winoverlay');
    if (!overlay) return;

    const modals = overlay.querySelectorAll('.win_white');
    const buttons = document.querySelectorAll(
        '.callme, .request, button.getoffer, .btn.buy-kredit , .fastorder, .callcall, .callme.banner-coffee-link'
    );

    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            e.stopPropagation();
        })
    })

    function hideAllModals() {
        modals.forEach(m => {
            m.style.display = 'none';
            m.style.opacity = '0';
        });
    }

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.target;
            const subject = btn.dataset.subject;

            hideAllModals();

            const modal = overlay.querySelector(target);
            if (modal) {
                modal.style.display = 'block';
                if (subject !== undefined) {
                    modal.querySelector('.zvonok').textContent = subject
                }
                requestAnimationFrame(() => {
                    modal.style.opacity = '1';
                });
            }

            overlay.classList.add('visible');
        });
    });

    overlay.querySelectorAll('.open_close').forEach(closeBtn => {
        closeBtn.addEventListener('click', () => {
            overlay.classList.remove('visible');
            hideAllModals();
        });
    });

    overlay.addEventListener('click', e => {
        if (e.target === overlay) {
            overlay.classList.remove('visible');
            hideAllModals();
        }
    });
});


/* ============================================================
   СБОР ДАННЫХ ФОРМЫ
============================================================ */
const getFeedback = form => {
    const data = Object.fromEntries(new FormData(form));

    data.phone = data.ft + data.code + data.phone;
    delete data.ft;
    delete data.code;

    data.template = form.dataset.template;
    data.subject = form.dataset.subject;
    data.date = new Date().toLocaleDateString('ru-RU');

    return data;
};


/* ============================================================
   ОТПРАВКА ФОРМЫ (AJAX)
============================================================ */
const sendFeedback = async (form, data) => {

    const submitButtons = form.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(btn => {
        btn.disabled = true;
        btn.textContent = 'Отправка...';
    });

    try {
        const res = await fetch('index.php?route=common/feedback', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data),
        });

        if (!res.ok) {
            showErrorMessage(`Ошибка ${res.status}: не удалось отправить заявку`);
            return;
        }

        form.reset();

        const modal = form.closest('.win_white');
        if (modal) {
            modal.style.opacity = '0';
            setTimeout(() => modal.style.display = 'none', 300);
        }

        const overlay = document.querySelector('.winoverlay');
        if (overlay) overlay.classList.remove('visible');

        showSuccessMessage();

    } catch (err) {
        showErrorMessage('Ошибка соединения. Попробуйте позже.');
    } finally {
        submitButtons.forEach(btn => {
            btn.disabled = false;
            btn.textContent = 'Отправить заявку';
        });
    }
};


/* ============================================================
   CALLTOUCH
============================================================ */
const sendCalltouchData = data => {
    const ct_site_id = '49728';

    jQuery.ajax({
        url: `https://api.calltouch.ru/calls-service/RestAPI/requests/${ct_site_id}/register/`,
        type: 'POST',
        dataType: 'json',
        data: {
            fio: data.name,
            phoneNumber: data.phone,
            email: data.email,
            subject: data.subject,
            sessionId: window.call_value,
            requestUrl: location.href,
            comment: data.note
        }
    });
};


/* ============================================================
   ОБРАБОТКА ВСЕХ ФОРМ ВНУТРИ МОДАЛОК
============================================================ */
document.addEventListener('DOMContentLoaded', () => {

    const forms = document.querySelectorAll('.win_white form, .feedback-form, #winProduct, .leasing-form');
    // const form = document.querySelector('.feedback-form');
    // const winProductForm = document.querySelector('#winProduct');


    forms.forEach(form => {

        form.querySelectorAll('a').forEach(a => {
            a.setAttribute('target', '_blank');
            a.setAttribute('rel', 'noopener noreferrer');
        });

        form.addEventListener('submit', e => {
            e.preventDefault();

            const data = getFeedback(form);
            const agreement = form.querySelector('input[name="agreement"]');

            if (agreement && !agreement.checked) {
                highlightAgreement(agreement);
                showErrorMessage('Поставьте галочку согласия на обработку данных');
                return;
            }

            sendFeedback(form, data);
            sendCalltouchData(data);
        });
    });
    //
    // form?.addEventListener('submit', e => {
    //     e.preventDefault();
    //
    //     const data = getFeedback(form);
    //     const agreement = form.querySelector('input[name="agreement"]');
    //
    //     if (agreement && !agreement.checked) {
    //         highlightAgreement(agreement);
    //         showErrorMessage('Поставьте галочку согласия на обработку данных');
    //         return;
    //     }
    //
    //     sendFeedback(form, data);
    //     sendCalltouchData(data);
    // })
    //
    // winProductForm?.addEventListener('submit', e => {
    //     e.preventDefault();
    //
    //     const data = getFeedback(form);
    //     const agreement = form.querySelector('input[name="agreement"]');
    //
    //     if (agreement && !agreement.checked) {
    //         highlightAgreement(agreement);
    //         showErrorMessage('Поставьте галочку согласия на обработку данных');
    //         return;
    //     }
    //
    //     sendFeedback(form, data);
    //     sendCalltouchData(data);
    // })
});


/* ============================================================
   ОЧИСТКА ТОСТОВ ПРИ РЕСАЙЗЕ
============================================================ */
window.addEventListener('resize', () => {
    activeToasts.forEach(t => t.remove());
    activeToasts = [];
});
