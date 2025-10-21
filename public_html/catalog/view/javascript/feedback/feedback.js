'use strict' //Использование директивы для включения строгого режима

const feedbackForm = document.querySelector('#feedback');// Ищем форму
const mainModalFeedbackForm = document.querySelector('#winMain');
const productModalFeedbackForm = document.querySelector('#winProduct');
const overlay = document.querySelector('.winoverlay');
const catchFeedbackForm = document.querySelector('#catchform2');
const creditFeedbackForm = document.querySelector('#fast');
let today = new Date();
let humanizeToday = today.toLocaleDateString('ru-RU');
const showSuccessMessage = () => {
    Toastify(
        {
            text:'Вы успешно отправили форму!',
            style:{
                background:'#4a259b',
                right:'45vw',
                padding:'20px'
            }
        }
    ).showToast();
};
const showErrorMessage = (status) => {
    Toastify({
        text:"Ответ от сервера:" + status + ",не удалось обработать вашу заявку, попробуйте позже",
        style:{
            right:'35vw',
            padding:'20px',
            background: 'red',
        }
    }).showToast();
};
const sendCalltouchData = (form, formData) => {

    const ct_site_id = '49728';
    let ct_data = {
        fio: formData.name,
        phoneNumber: formData.phone,
        email: formData.email,
        subject: formData.subject || 'Заявка с сайта vend-shop.com',
        sessionId: window.call_value,
        requestUrl: location.href,
        comment: formData.note
    };
    jQuery.ajax({
        url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/'+ct_site_id+'/register/',
        dataType: 'json',
        type: 'POST',
        data: ct_data,
    });
};
const getFeedback = (form) => {
    const formData = new FormData(form);//Создаём образец FormData из данных фидбек-формы
    const feedBackData = Object.fromEntries(formData);//Создаём объект из FormData

    feedBackData.phone = feedBackData.ft + feedBackData.code + feedBackData.phone;
    delete feedBackData.ft;
    delete feedBackData.code;
    feedBackData.template = form.dataset.template;
    feedBackData.date = humanizeToday;
    feedBackData.subject = form.dataset.subject;

    return feedBackData;
}
const sendFeedback = (form, data) => {
    fetch('index.php?route=common/feedback',
        {
            method:'POST',
            body: JSON.stringify(data),
            headers:{
                "Content-Type": "application/json",
            },
        }
    )
        .then((res) =>
            {

                if (res.status === 200){
                    form.reset();
                    if (form.id === 'winMain' || form.id === 'winProduct' || form.id === 'fast'){
                        form.closest('.win_white').style.display = 'none';
                        if (overlay) {
                            overlay.style.display = 'none';
                        }
                    }
                    //Сообщение об успешной отправке формы
                    showSuccessMessage();

                }else
                {
                    //Сообщение об ошибке
                    showErrorMessage(res.status);
                }

            }

        );
};

if (feedbackForm) {
    feedbackForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const feedBackData = getFeedback(feedbackForm);
        //Отправляем при помощи Fetch API данные на php роут
        sendFeedback(feedbackForm, feedBackData);
        sendCalltouchData(feedbackForm, feedBackData);
    });
}

if (mainModalFeedbackForm){
    mainModalFeedbackForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const feedBackData = getFeedback(mainModalFeedbackForm);

        sendFeedback(mainModalFeedbackForm, feedBackData);
        sendCalltouchData(mainModalFeedbackForm, feedBackData);
    });
}

if (productModalFeedbackForm){
    productModalFeedbackForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const feedBackData = getFeedback(productModalFeedbackForm);

        sendFeedback(productModalFeedbackForm, feedBackData);
        sendCalltouchData(productModalFeedbackForm, feedBackData);
    });
}

if (catchFeedbackForm){
    catchFeedbackForm.addEventListener('submit',(event) => {
        event.preventDefault();
        const feedbackData = getFeedback(catchFeedbackForm);

        sendFeedback(catchFeedbackForm,feedbackData);
        sendCalltouchData(catchFeedbackForm, feedBackData);
    });
}

if (creditFeedbackForm){
    creditFeedbackForm.addEventListener('submit', (event) => {
        event.preventDefault();
        const feedbackData = getFeedback(creditFeedbackForm);

        sendFeedback(creditFeedbackForm, feedbackData);
        sendCalltouchData(creditFeedbackForm, feedbackData);
    });
}