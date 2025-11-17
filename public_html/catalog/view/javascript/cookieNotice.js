const COOKIE_KEY = 'cookieAccepted';
const cookieNotice = document.querySelector('#cookieNotice');
const cookieBtn = document.querySelector('#cookieAcceptBtn');
const showHelp = document.querySelector('.showHelp');

// JivoSite кнопка появится ПОЗЖЕ → используем MutationObserver
let jivoBtn = null;

const findJivoButton = () => {
    if (jivoBtn) return;

    // Jivo рендерит <jdiv>, поэтому ищем ТЕГ jdiv
    const btn = document.querySelector('jdiv');
    if (btn) {
        jivoBtn = btn;
        jivoBtn.style.display = 'none'; // скрываем до согласия
    }
};

// Наблюдаем за DOM, чтобы поймать jdiv, когда он появится
const observer = new MutationObserver(() => findJivoButton());
observer.observe(document.body, { childList: true, subtree: true });

// Стартовая попытка
findJivoButton();

// Скрываем help до согласия
showHelp?.classList?.add('hidden');

// Безопасная загрузка GTM
const loadGTM = () => {
    window.__gtmLoaded = true;

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'gtm.start': Date.now(),
        event: 'gtm.js'
    });

    const script = document.createElement('script');
    script.async = true;
    script.src = 'https://www.googletagmanager.com/gtm.js?id=GTM-WWRJPN3';

    script.referrerPolicy = 'strict-origin-when-cross-origin';
    script.crossOrigin = 'anonymous';

    document.head.appendChild(script);
};

// Разрешение аналитики
const enableAnalytics = () => {
    loadGTM();

    showHelp?.classList?.remove('hidden');

    if (jivoBtn) jivoBtn.style.display = 'block';

    if (typeof ym === 'function') {
        ym(22761283, 'reachGoal', 'click-question');
    }
};

// Пользователь уже дал согласие
if (localStorage.getItem(COOKIE_KEY)) {
    enableAnalytics();
} else {
    setTimeout(() => {
        cookieNotice.classList.remove('hidden');
        cookieNotice.classList.add('visible');
    }, 1000);
}

// Принимаем cookies
cookieBtn.addEventListener('click', (e) => {
    cookieBtn.disabled = true;

    localStorage.setItem(COOKIE_KEY, '1');

    cookieNotice.classList.remove('visible');
    setTimeout(() => cookieNotice.remove(), 400);

    enableAnalytics();
});
