document.addEventListener('DOMContentLoaded', () => {
    const aside = document.querySelector('.policies');
    const hrefs = ['o-kompanii','oplata','servisnyy-tsentr','arenda'];

    if (hrefs.includes(location.href.split('/')[3])) {
        aside.classList.add('hidden');
    }
})