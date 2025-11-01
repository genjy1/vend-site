<div class="winoverlay"></div>
    <div class="win_white">
      <a class="open_close" href="javascript:void(0)"></a>
      <div class="zvonok">Обратный звонок</div>
      <div class="formochka">
        <form data-template="request" id="winMain" data-subject="Обратный звонок">
          Имя <span>*</span> <br>
          <input type="text" name="name" required>
          Номер Вашего телефона<span>*</span><br>
          <div class="teldiv">
            <input type="tel" name="ft" maxlength="2" value = "+7" required >
            <input type="tel" name="code" value = "" placeholder="123" pattern="^\d+$" maxlength="3" required >
            <input type="tel" name="phone" value = "" placeholder="456 78 90" pattern="^\d+$" maxlength="8" required >
          </div>
          <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?>">
          <button id="submit" type="submit" class="submit">Отправить заявку</button>
            <div class="agreement-container">
              <input type="checkbox" name="agreement" id="CallbackAgreement">
              <label for="CallbackAgreement" class="prv agreement-label">Даю <a href="/agreement">согласие на обработку моих персональных данных</a> в соответствии с
                <a href="/privacy">политикой конфиденциальности</a> в целях обработки моего обращения и взаимодействия с компанией.</label>
            </div>

        </form>
      </div>
    </div>
    <div class="win_white">
      <a class="open_close" href="javascript:void(0)"></a>
      <div class="spasibo">Спасибо!</div>
      <div class="zaiavka">Ваша заявка успешно отправленна.</div>
      <div class="zaiavka_2">Наши специалисты скоро с вами свяжутся</div>
    </div>
    <div class="win_white">
      <a class="open_close" href="javascript:void(0)"></a>
      <div class="zvonok">Запросить цену</div>
      <div class="formochka">
        <form data-template="request" data-subject="Запрос цены" id="request">
          Имя <span>*</span> <br>
          <input type="text" name="name">
          Номер Вашего телефона<span>*</span><br>
          <div class="teldiv">
            <input type="tel" name="ft" maxlength="2" value = "+7" required >
            <input type="tel" name="code" value = "" placeholder="123" pattern="^\d+$" maxlength="3" required >
            <input type="tel" name="phone" value = "" placeholder="456 78 90" pattern="^\d+$" maxlength="8" required >
          </div>
          <input type="hidden" name="product">
          <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
          
          <button id="submit" type="submit" class="submit">Отправить заявку</button>
            <div class="agreement-container">
              <input type="checkbox" name="agreement" id="AgreementRequestPrice">
              <label for="AgreementRequestPrice" class="prv agreement-label">Даю <a href="/agreement">согласие на обработку моих персональных данных</a> в соответствии с
                <a href="/privacy">политикой конфиденциальности</a> в целях обработки моего обращения и взаимодействия с компанией.</label>
            </div>
        </form>
      </div>
    </div>
    
    <div class="win_white">
      <a class="open_close" href="javascript:void(0)"></a>
      <div class="zvonok">Быстрый заказ</div>
      <div class="formochka">
        <form data-template="request" data-subject="Быстрый заказ" id="fast">
          Имя <span>*</span> <br>
          <input type="text" name="name">
          Номер Вашего телефона<span>*</span><br>
          <div class="teldiv">
            <input type="tel" name="ft" maxlength="2" value = "+7" required >
            <input type="tel" name="code" value = "" placeholder="123" pattern="^\d+$" maxlength="3" required >
            <input type="tel" name="phone" value = "" placeholder="456 78 90" pattern="^\d+$" maxlength="8" required >
          </div>
          <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
          <button id="submit" type="submit" class="submit">Отправить заявку</button>
          <div class="agreement-container">
            <input type="checkbox" name="agreement" id="agreement_request">
            <label for="agreement_request" class="prv agreement-label">Даю <a href="/agreement">согласие на обработку моих персональных данных</a> в соответствии с
              <a href="/privacy">политикой конфиденциальности</a> в целях обработки моего обращения и взаимодействия с компанией.</label>
          </div>
        </form>
      </div>
    </div>
    <div class="win_white" id="calc">
      <a class="open_close" href="javascript:void(0)"></a>
      <div class="zvonok">Калькулятор расчета прибыли</div>
      <div class="formochka">
        <form>
          <div class="title">Доход</div>
          <div><div>Средняя закупочная цена товара руб</div>
          <input type="text" value="1,3" id="zak"></div>
          <div><div>Средняя продажная цена товара руб</div>
          <input type="text" value="5" id="prod"></div>
          <div><div>Ожидаемое кол-во продаж в день</div>
          <input type="text" value="500" id="qd"></div>
          <div><div>Количество рабочих дней</div>
          <input type="text" value="30" id="days"></div>
          <div>Доход в месяц: <span id="money">60000</span> руб</div>

          <div class="title">Расход</div>
          <div><div>Cумма, которую Вы платите за аренду в месяц</div>
          <input type="text" value="0" id="arenda"></div>
          <div><div>Затраты на ГСМ, мойку, обслуживание (в месяц)
          Фиксированная сумма, которую Вы платите в месяц:</div>
          <input type="text" value="0" id="gsm"></div>
          <div><div>Затраты на заработную плату механика, обслуживающего автомат (в месяц).
          (Если Вы будете обслуживать автомат своими силами - 0 руб.)</div>
          <input type="text" value="0" id="salar"></div>
          <div><div>Стоимость 1Квт электроэнергии:</div>
          <input type="text" value="4" id="kvt"></div>
          <div><div>Потребляемая мощность автомата:</div>
          <input type="text" value="36" id="power"></div>
          <div>Ожидаемая прибыль в месяц: <span id="totalmon">56256.00</span> руб</div>
        </form>
      </div>
    </div>

    <div class="win_white" id="offer">
      <a class="open_close" href="javascript:void(0)"></a>
      <div class="zvonok">Оставить заявку</div>
      <div class="formochka">
        <form data-template="request" id="winProduct" data-subject="Обратный звонок">
          Имя <span>*</span> <br>
          <input type="text" name="name" required>
          Номер Вашего телефона<span>*</span><br>
          <div class="teldiv">
            <input type="tel" name="ft" maxlength="2" value = "+7" required >
            <input type="tel" name="code" value = "" placeholder="123" pattern="^\d+$" maxlength="3" required >
            <input type="tel" name="phone" value = "" placeholder="456 78 90" pattern="^\d+$" maxlength="8" required >
          </div>
                    <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
          <div id="options"></div>
          <div id="options2"></div>
          
          <button id="submit" type="submit" class="submit">Отправить заявку</button>
            <div class="agreement-container">
              <input type="checkbox" name="agreement" id="agreement_main">
              <label for="agreement_main" class="prv agreement-label">Даю <a href="/agreement">согласие на обработку моих персональных данных</a> в соответствии с
                <a href="/privacy">политикой конфиденциальности</a> в целях обработки моего обращения и взаимодействия с компанией.</label>
            </div>
        </form>
      </div>
    </div>