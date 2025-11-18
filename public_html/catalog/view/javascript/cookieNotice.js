// ==============================
//  CONFIG
// ==============================
const COOKIE_KEY = 'cookieAccepted';
const GTM_ID = 'GTM-WWRJPN3';

// ==============================
//  DOM ELEMENTS
// ==============================
const cookieNotice = document.querySelector('#cookieNotice');
const cookieBtn = document.querySelector('#cookieAcceptBtn');
const showHelp = document.querySelector('.showHelp');


// ==============================
//  JIVOSITE HANDLING
// ==============================
let jivoBtn = null;

const findJivoButton = () => {
    if (jivoBtn) return;

    const btn = document.querySelector('jdiv');
    if (btn) {
        jivoBtn = btn;
        jivoBtn.style.display = 'none';
    }
};

const observeJivo = () => {
    const observer = new MutationObserver(() => findJivoButton());
    observer.observe(document.body, { childList: true, subtree: true });
    findJivoButton();
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
    loadGTM();

    if (showHelp?.classList) {
        showHelp.classList.remove('hidden');
    }

    if (jivoBtn) {
        jivoBtn.style.display = 'block';
    }

    if (typeof ym === 'function') {
        ym(22761283, 'reachGoal', 'click-question');
    }
};


// ==============================
//  COOKIE NOTICE LOGIC
// ==============================
const showCookieNotice = () => {
    cookieNotice?.classList?.remove('hidden');
    cookieNotice?.classList?.add('visible');
};

const hideCookieNotice = () => {
    cookieNotice?.classList?.remove('visible');
    setTimeout(() => cookieNotice?.remove(), 400);
};


// ==============================
//  INITIALIZATION
// ==============================
const init = () => {
    observeJivo();

    showHelp?.classList?.add('hidden');

    const consentGiven = Boolean(localStorage.getItem(COOKIE_KEY));

    if (consentGiven) {
        enableAnalytics();
        cookieNotice?.classList?.remove('visible');
        cookieNotice?.classList?.add('hidden');
    } else {
        setTimeout(showCookieNotice, 1000);
    }

    cookieBtn?.addEventListener('click', () => {
        cookieBtn.disabled = true;

        localStorage.setItem(COOKIE_KEY, '1');

        hideCookieNotice();
        enableAnalytics();
    });
};

init();
