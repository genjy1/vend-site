    <footer>
      <div class="lc"><a id="footerlogo"><img class="lazy" data-src="image/logotype_bottom.svg"></a>
        <div class="fcont">
          <div class="footcol">
            <div class="title">Библиотека Вендора</div>
            <div> <a href="<? echo $origin_server ?>blog/s-chego-nachat-biznes/">С чего начать бизнес</a></div>
            <div><a href="<? echo $origin_server ?>blog/oshibki-na-nachalnom-etape-biznesa/">Ошибки на начальном этапе бизнеса</a></div>
            <div> <a href="<? echo $origin_server ?>blog/yuridicheskie-aspekty-vendinga/">Юридические аспекты вендинга</a></div>
            <div> <a href="<? echo $origin_server ?>blog/biznes-idei/">Бизнес идеи</a></div>
            <div> <a href="<? echo $origin_server ?>blog/optsiya-bonus">Как увеличить продажи</a></div>
          </div>
          <div class="footcol">
            <div class="footmenu"><a href="<? echo $origin_server ?>o-kompanii/">О компании</a><a href="<? echo $origin_server ?>oplata/">Оплата и доставка</a><a href="<? echo $origin_server ?>photos/">Галерея </a><a href="<? echo $origin_server ?>servisnyy-tsentr/">Сервисный центр</a><a href="<? echo $origin_server ?>kontakty/">Контакты</a></div>
            <div class="phones">
              <div class="phone">
                <div><a href="tel:88007757349">8 (800) 775-73-49</a></div>
                <div>Бесплатный звонок по России</div>
              </div>
              <div class="phone">
                <div><a href="tel:84953803775">8 (495) 380-37-75</a></div>
                <div>Для звонков по Москве</div>
              </div>
            </div>
            <div class="callme">Перезвоните мне</div>
          </div>
          <div class="footcol">
            <div class="title">Информация</div>
            <div> <a href="<? echo $origin_server ?>blog/torgovyy-avtomat-v-lizing/">Торговый автомат в лизинг</a></div>
            <div> <a href="<? echo $origin_server ?>blog/torgovyj-avtomat-v-kredit/">Торговый автомат в кредит</a></div>
            <div> <a href="<? echo $origin_server ?>blog/besprotsentnaya-rassrochka/">Беспроцентная рассрочка</a></div>
            <div> <a href="<? echo $origin_server ?>blog/mesta-pod-avtomaty/">Места под автоматы</a></div>
            <div> <a href="<? echo $origin_server ?>blog/vopros-otvet/">Вопрос-ответ</a></div>
            <div> <a href="<? echo $origin_server ?>sitemap/">Карта сайта</a></div>
             
          </div>
        </div>
        <div class="fbottom"><a id="gotolib" href="<? echo $lib ?>">Перейти в библиотеку</a>
          <div class="search">
            <input type="text" value="Поиск по сайту" name="search" onfocus="if (this.value==this.defaultValue) this.value = ''"
            onblur="if (this.value=='') this.value = this.defaultValue">
            <button></button>
          </div>
        </div>
        <div class="cop">2003-<?php echo date('Y'); ?> © VendShop – надежные <a href="https://vend-shop.com">торговые автоматы для Вашего вендингового бизнеса</a></div>
      </div>
    </footer>
    <div id="cookieNotice" class="cookie-notice visible" role="alertdialog" aria-live="polite" aria-label="Сообщение о cookie">
      <p>
        Мы используем файлы <a href="/cookie">cookie</a> и <a href="/metrika">Яндекс. Метрику</a> для улучшения работы сайта.
      </p>
      <button id="cookieAcceptBtn">Согласен</button>
    </div>


    <?php echo $feedback; ?>

    <?php echo $scripts; ?>

  <script>
    var _ctreq_jivo = function (sub) {
        var sid = '49728';
        var jc = jivo_api.getContactInfo(); var fio = ''; var phone = ''; var email = '';
        if (!!jc.client_name) { fio = jc.client_name; } if (!!jc.phone) { phone = jc.phone; } if (!!jc.email) { email = jc.email; }
        var ct_data = { fio: fio, phoneNumber: phone, email: email, subject: sub, requestUrl: location.href, sessionId: window.call_value };
        var request = window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
        var post_data = Object.keys(ct_data).reduce(function (a, k) { if (!!ct_data[k]) { a.push(k + '=' + encodeURIComponent(ct_data[k])); } return a }, []).join('&');
        var url = 'https://api.calltouch.ru/calls-service/RestAPI/' + sid + '/requests/orders/register/';
        if (!window.ct_snd_flag) {
            window.ct_snd_flag = 1; setTimeout(function () { window.ct_snd_flag = 0; }, 10000);
            request.open("POST", url, true); request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded'); request.send(post_data);
        }
    }
    window.jivo_onIntroduction = function () { _ctreq_jivo('JivoSite посетитель оставил контакты'); }
    window.jivo_onCallStart = function () { _ctreq_jivo('JivoSite обратный звонок'); }
  </script>

</body>
</html>