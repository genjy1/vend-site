document.addEventListener('DOMContentLoaded', () => {
    const link = document.querySelector('header .bottom #menu > li:nth-child(4) > a');

    if (link) {
        link.textContent = 'ВОДА НА ПРЕДПРИЯТИЯ';

        setInterval(() => {
            link.textContent = link.textContent === 'ВОДА НА ПРЕДПРИЯТИЯ' ? 'Новинка' : 'ВОДА НА ПРЕДПРИЯТИЯ';
        }, 5000);


    } else {
        console.warn("Элемент меню не найден!");
    }
});
