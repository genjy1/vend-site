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
          Email<br>
          <input type="email" name="email" required>
          <div>
              Ваш регион <span>*</span><br>
              <input type="text" name="region" required>
          </div>
          <div class="radios">
              <div>
                <input type="radio" checked="checked" name="firma" id="fiz" value="Физическое лицо"> <label for="fiz"> <span></span>Физическое лицо</label>
              </div>
              <div>
                <input type="radio" name="firma" id="jur" value="Юридическое лицо"> <label for="jur"> <span></span>Юридическое лицо </label>
              </div>
          </div>
          <div>
              Какое количество автоматов интересует? <span>*</span><br>
              <input type="text" name="amount" required value="1">
          </div>
          <div>
              <input type="checkbox" class="credit-input" name="credit" id="crdit1"><label for="crdit1"> Кредит/лизинг</label> 
          </div>
          <div class="radios has">
                <br>
                Eсть ли у Вас автоматы? <br><br>
              <div>
                <input type="radio" checked="checked" name="has" id="has_y" value="Да"> <label for="has_y"> <span></span>Да</label>
              </div>
              <div>
                <input type="radio" name="has" id="has_no" value="Нет"> <label for="has_no"> <span></span>Нет </label>
              </div>
          </div>
          <div class="note">
              Какие автоматы интересуют? <span>*</span>
              <textarea name="note" required></textarea>
          </div>
          <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?>">
          <button id="submit" class="submit">Отправить заявку</button>
          <div class="prv">
            Нажимая на кнопку "отправить", вы даете согласие на обработку <a href="https://vend-shop.com/privacy/">персональных данных</a>.
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
          Email<br>
          <input type="email" name="email" required>
          <div>
              Ваш регион <span>*</span><br>
              <input type="text" name="region" required>
          </div>
          <div class="radios">
              <div>
                <input type="radio" checked="checked" name="firma" id="fiz_f" value="Физическое лицо"> <label for="fiz_f"> <span></span>Физическое лицо</label>
              </div>
              <div>
                <input type="radio" name="firma" id="jur_f" value="Юридическое лицо"> <label for="jur_f"> <span></span>Юридическое лицо </label>
              </div>
          </div>
          <div>
              Какое количество автоматов интересует? <span>*</span><br>
              <input type="text" name="amount" required value="1">
          </div>
          <div>
              <input type="checkbox" class="credit-input" name="credit" id="crdit"><label for="crdit"> Кредит/лизинг</label> 
          </div>
          <div class="radios has">
                <br>
                Eсть ли у Вас автоматы? <br><br>
              <div>
                <input type="radio" checked="checked" name="has" id="has_y" value="Да"> <label for="has_y"> <span></span>Да</label>
              </div>
              <div>
                <input type="radio" name="has" id="has_no" value="Нет"> <label for="has_no"> <span></span>Нет </label>
              </div>
          </div>
          <div class="note">
              Какие автоматы интересуют? <span>*</span>
              <textarea name="note" required></textarea>
          </div>
          <input type="hidden" name="product">
          <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
          
          <button id="submit" class="submit">Отправить заявку</button>
          <div class="prv">
            Нажимая на кнопку "отправить", вы даете согласие на обработку <a href="https://vend-shop.com/privacy/">персональных данных</a>.
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
          Email<br>
          <input type="email" name="email" required>
          <div>
              Ваш регион <span>*</span><br>
              <input type="text" name="region" required>
          </div>
          <div class="radios">
              <div>
                <input type="radio" checked="checked" name="firma" id="fiz_s" value="Физическое лицо"> <label for="fiz_s"> <span></span>Физическое лицо</label>
              </div>
              <div>
                <input type="radio" name="firma" id="jur_s" value="Юридическое лицо"> <label for="jur_s"> <span></span>Юридическое лицо </label>
              </div>
          </div>
          <div>
              Какое количество автоматов интересует? <span>*</span><br>
              <input type="text" name="amount" required value="1">
          </div>
          <div>
              <input type="checkbox" class="credit-input" name="credit" id="crdit_a"><label for="crdit_a"> Кредит/лизинг</label> 
          </div>
          <div class="radios has">
                <br>
                Eсть ли у Вас автоматы? <br><br>
              <div>
                <input type="radio" checked="checked" name="has" id="has_y_a" value="Да"> <label for="has_y_a"> <span></span>Да</label>
              </div>
              <div>
                <input type="radio" name="has" id="has_no_a" value="Нет"> <label for="has_no_a"> <span></span>Нет </label>
              </div>
          </div>
          <div class="note">
              Какие автоматы интересуют? <span>*</span>
              <textarea name="note" required></textarea>
          </div>
          <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
          <button id="submit" class="submit">Отправить заявку</button>
          <div class="prv">
            Нажимая на кнопку "отправить", вы даете согласие на обработку <a href="https://vend-shop.com/privacy/">персональных данных</a>.
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
          Email<br>
          <input type="email" name="email" required>
          <div>
              Ваш регион <span>*</span><br>
              <input type="text" name="region" required>
          </div>
          <div class="radios">
              <div>
                <input type="radio" checked="checked" name="firma" id="fiz_oz" value="Физическое лицо"> <label for="fiz_oz"> <span></span>Физическое лицо</label>
              </div>
              <div>
                <input type="radio" name="firma" id="jur_oz" value="Юридическое лицо"> <label for="jur_oz"> <span></span>Юридическое лицо </label>
              </div>
          </div>
          <div>
              Какое количество автоматов интересует? <span>*</span><br>
              <input type="text" name="amount" required value="1">
          </div>
          <div>
              <input type="checkbox" class="credit-input" name="credit" id="crdit_oz"><label for="crdit_oz"> Кредит/лизинг</label> 
          </div>
          <div class="radios has">
                <br>
                Eсть ли у Вас автоматы? <br><br>
              <div>
                <input type="radio" checked="checked" name="has" id="has_y_oz" value="Да"> <label for="has_y_oz"> <span></span>Да</label>
              </div>
              <div>
                <input type="radio" name="has" id="has_no_oz" value="Нет"> <label for="has_no_oz"> <span></span>Нет </label>
              </div>
          </div>
          <div class="note">
              Какие автоматы интересуют? <span>*</span>
              <textarea name="note" required></textarea>
          </div>
          <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
          <div id="options"></div>
          <div id="options2"></div>
          
          <button id="submit" class="submit">Отправить заявку</button>
          <div class="prv">
            Нажимая на кнопку "отправить", вы даете согласие на обработку <a href="https://vend-shop.com/privacy/">персональных данных</a>.
          </div>
        </form>
      </div>
    </div>