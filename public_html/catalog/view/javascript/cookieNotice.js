// ==============================
//  CONFIG
// ==============================
const COOKIE_KEY = 'cookieAccepted';
const GTM_ID = 'GTM-WWRJPN3';
const YM_COUNTER = 22761283;

// ==============================
//  DOM ELEMENTS
// ==============================
const cookieNotice = document.querySelector('#cookieNotice');
const cookieBtn = document.querySelector('#cookieAcceptBtn');
const showHelp = document.querySelector('.showHelp');

// ==============================
//  STATE
// ==============================
let jivoBtn = null;
let jivoObserverStarted = false;


// ==============================
//  JIVOSITE HANDLING
// ==============================
const detectJivo = () => {
    if (jivoBtn) return;

    const btn = document.querySelector('jdiv');
    if (!btn) return;

    jivoBtn = btn;

    // Показываем или скрываем в зависимости от согласия
    if (window.__analyticsEnabled) {
        jivoBtn.style.display = 'block';
    } else {
        jivoBtn.style.display = 'none';
    }
};

const observeJivo = () => {
    if (jivoObserverStarted) return;
    jivoObserverStarted = true;

    const observer = new MutationObserver(detectJivo);
    observer.observe(document.body, { childList: true, subtree: true });

    detectJivo();
};


// ==============================
//  GTM LOADING
// ==============================
const loadGTM = () => {
    if (window.__gtmLoaded) return;
    window.__gtmLoaded = true;

    window.dataLayer = window.dataLayer || [];
    window.dataLayer.push({
        'gtm.start': Date.now(),
        event: 'gtm.js'
    });

    const script = document.createElement('script');
    script.async = true;
    script.src = `https://www.googletagmanager.com/gtm.js?id=${GTM_ID}`;
    script.referrerPolicy = 'strict-origin-when-cross-origin';
    script.crossOrigin = 'anonymous';

    document.head.appendChild(script);
};


// ==============================
//  USER CONSENT + ANALYTICS
// ==============================
const enableAnalytics = () => {
    if (window.__analyticsEnabled) return;
    window.__analyticsEnabled = true;

    loadGTM();
    detectJivo();

    // показываем кнопку help
    if (showHelp) {
        showHelp.classList.remove('hidden');
    }

    // если jivo уже есть — покажем
    if (jivoBtn) {
        jivoBtn.style.display = 'block';
    }

    // Яндекс.Метрика
    if (typeof ym === 'function') {
        ym(YM_COUNTER, 'reachGoal', 'click-question');
    }
};


// ==============================
//  COOKIE NOTICE LOGIC
// ==============================
const showCookieNotice = () => {
    if (cookieNotice) {
        cookieNotice.classList.remove('hidden');
        cookieNotice.classList.add('visible');
    }
};

const hideCookieNotice = () => {
    if (!cookieNotice) return;

    cookieNotice.classList.remove('visible');
    setTimeout(() => {
        if (cookieNotice) cookieNotice.remove();
    }, 400);
};


// ==============================
//  INITIALIZATION
// ==============================
const init = () => {
    observeJivo();

    if (showHelp) {
        showHelp.classList.add('hidden');
    }

    const consentGiven = Boolean(localStorage.getItem(COOKIE_KEY));

    if (consentGiven) {
        enableAnalytics();
        if (cookieNotice) cookieNotice.remove();
    } else {
        setTimeout(showCookieNotice, 1000);
    }

    if (cookieBtn) {
        cookieBtn.addEventListener('click', () => {
            cookieBtn.disabled = true;

            localStorage.setItem(COOKIE_KEY, '1');

            hideCookieNotice();
            enableAnalytics();
        });
    }
};

init();
