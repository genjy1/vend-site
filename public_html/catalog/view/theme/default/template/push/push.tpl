<link rel="manifest" href="/manifest.json">
<meta name="token" content="" id="token">
<!--
<script>
setTimeout(() => {
  var script = document.createElement('script');
  script.src = "https://www.gstatic.com/firebasejs/5.8.2/firebase.js";
  document.getElementsByTagName('head')[0].appendChild(script);
}, 10000);
setTimeout(() => {
  document.getElementsByTagName('head')[0].append(`<script>
  // Initialize Firebase
  var config = {
    apiKey: "AIzaSyCuv-0sti-vLlP3NzXyCpT2A55GN21xpDo",
    authDomain: "vend-shop-push.firebaseapp.com",
    databaseURL: "https://vend-shop-push.firebaseio.com",
    projectId: "vend-shop-push",
    storageBucket: "vend-shop-push.appspot.com",
    messagingSenderId: "548274249113"
  };
  firebase.initializeApp(config);</\script>`);

  const messaging = firebase.messaging();
  document.getElementById('token').content = 'NO LOAD TOKEN';

  //запрос на показ Web-PUSH браузеру
  messaging.requestPermission()
    .then(function() {
      console.log('Notification permission granted.');
      // Если нотификация разрешена, получаем токен.
      messaging.getToken()
      .then(function(currentToken) {
        if (currentToken) {
          console.log("sendToken");
          //отправка токена на сервер
          SendTokenToServer(currentToken);
          document.getElementById('token').content = currentToken;
        } else {
          console.log('No Instance ID token available. Request permission to generate one.');
        }
      })
      .catch(function(err) {
        console.log('An error occurred while retrieving token. ', err);
        showToken('Error retrieving Instance ID token. ', err);
      });
      // ...
    })
    .catch(function(err) {
      console.log('Unable to get permission to notify.', err);
    });
 
 
    messaging.onMessage(function(payload) {
      console.log('Message received. ', payload);
      // регистрируем пустой ServiceWorker каждый раз
      navigator.serviceWorker.register('firebase-messaging-sw.js');
      // запрашиваем права на показ уведомлений если еще не получили их
      Notification.requestPermission(function(result) {
        if (result === 'granted') {
          navigator.serviceWorker.ready.then(function(registration) {
            // теперь мы можем показать уведомление
            return registration.showNotification(payload.notification.title, payload.notification);
          }).catch(function(error) {
            console.log('ServiceWorker registration failed', error);
          });
        }
      });
    });

  //сохранение токена
    function SendTokenToServer(currentToken) {
      xmlhttp=new XMLHttpRequest();
      xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
          console.log(this.responseText);
        }
      }
      console.log("token - " + currentToken);
      // $("header").append(currentToken + "                      ///");
      xmlhttp.open("GET","index.php?route=push/push/save&token="+currentToken,true);
      xmlhttp.send();
    }
}, 11000);
</script>
<!--<script defer src="https://www.gstatic.com/firebasejs/5.8.2/firebase.js"></script>
-->
