'use strict' //Использование директивы для включение строгого режима

const feedbackForm = document.querySelector('#feedback');// Ищем форм

feedbackForm.addEventListener('submit', (event) => {
    event.preventDefault();

    const data = new FormData(feedbackForm);//Создаём образец FormData из данных фидбек-формы
    const feedBackData = Object.fromEntries(data);//Создаём объект из FormData
    const ct_site_id = '49728';// Calltouch site id, используется в для отправки на REST API ниже
    let ct_data = {
        // Объект данных для отправки в Calltouch
        fio: feedBackData.name,
        phoneNumber: String(feedBackData.ft + feedBackData.code + feedBackData.phone),
        email: feedBackData.email,
        subject: feedbackForm.dataset.subject,
        requestUrl: location.href,
        sessionId: window.ct('calltracking_params','a32r4yxz').sessionId
    };


    //Отправляем при помощи Fetch API данные на php роут
    fetch('index.php?route=common/feedback',
        {
            method:'POST',
            body: JSON.stringify(feedBackData),
            headers:{
                "Content-Type": "application/json",
            },
        }
    ).then((res) =>
        {
        console.log(res.status);// Выводим статус, полученный от сервера
            if (res.status == 200){
                feedbackForm.reset();
            }
        }
    );


    //Отправка данных для Calltouch
    jQuery.ajax({
        url: 'https://api.calltouch.ru/calls-service/RestAPI/requests/'+ct_site_id+'/register/',
        dataType: 'json',
        type: 'POST',// HTTP метод
        data: ct_data,
    });

})