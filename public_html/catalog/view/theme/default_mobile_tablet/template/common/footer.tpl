    <footer>
      <div class="lc"><a id="footerlogo"><img src="image/logotype_bottom.svg"></a>
        <div class="footmenu"><a href="/o-kompanii/">О компании</a><a href="/oplata/">Оплата и доставка</a><a href="/photos/">Галерея </a><a href="/servisnyy-tsentr/">Сервисный центр</a><a href="/kontakty/">Контакты</a></div>
        <div class="fcont">
          <div class="footcol">
            <div class="title">Библиотека Вендора</div>
            <div> <a href="/blog/s-chego-nachat-biznes/">С чего начать бизнес</a></div>
            <div><a href="/blog/oshibki-na-nachalnom-etape-biznesa/">Ошибки на начальном этапе бизнеса</a></div>
            <div> <a href="/blog/yuridicheskie-aspekty-vendinga/">Юридические аспекты вендинга</a></div>
            <div> <a href="/blog/biznes-idei/">Бизнес идеи</a></div>
          </div>
          <div class="footcol">
            <div class="title">Информация</div>
            <div> <a href="/blog/torgovyy-avtomat-v-lizing/">Торговый автомат в лизинг</a></div>
            <div> <a href="/blog/torgovyj-avtomat-v-kredit/">Торговый автомат в кредит</a></div>
            <div> <a href="/blog/besprotsentnaya-rassrochka/">Беспроцентная рассрочка</a></div>
            <div> <a href="/blog/mesta-pod-avtomaty/">Места под автоматы</a></div>
            <div> <a href="/blog/vopros-otvet/">Вопрос-ответ</a></div>
          </div>
          <div class="footcol">
            <div class="phones">
              <div class="phone">
                <div>8 (800) 775-73-49</div>
                <div>Бесплатный звонок по России</div>
              </div>
              <div class="phone">
                <div>8 (495) 380-37-75</div>
                <div>Для звонков по Москве</div>
              </div>
            </div>
            <div class="callme">Перезвоните мне</div>
          </div>
          
        </div>
        <div class="fbottom"><a id="gotolib" href="/biblioteka-vendora/">Перейти в библиотеку</a>
          <div class="search">
            <input type="text" value="Поиск по сайту" name="search" onfocus="if (this.value==this.defaultValue) this.value = ''"
onblur="if (this.value=='') this.value = this.defaultValue">
            <button></button>
          </div>
        </div>
        <div class="cop">2003-<?php echo date('Y');?> © VendShop – надежные торговые автоматы для Вашего бизнеса</div>
      </div>
    </footer>
    <?php echo $feedback; ?>

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


<div class="help">
      <h2>Какой тип автоматов Вас интересует?</h2>
      <div class="selects">
        <?php foreach($help['categories'] as $category){ ?>
          <div>
            <a href="<?php echo $category['href'] ?>?pomosch"> <?php echo $category['name'] ?> </a>
          </div>
        <?php } ?>
  
        <div>
          <select name="" id="helpcats">
            <?php foreach($categories as $category) { ?>
              <option value="<?php echo $category['href'] ?>?pomosch"><?php echo $category['name'] ?></option>
            <? } ?>
          </select>
        </div>


      </div>
      <a href="javascript:void(0)" class="close" id="pomoschnet">Спасибо, я сам разберусь</a>
    </div>

    <div class="showHelp"></div>


</div>
<style>
.citem{
  margin-bottom:3px;
  font-size:13px;
  padding:3px;
  width:270px;
}
</style>
<?php echo $scripts ?>
    <iframe id="arenzaWidget" src="https://crmpro.arenza.ru/partners/oec0z3hqc2Xo6DoPaaxV95TBoVqBMvl-8QOordPLLHs/widget?iframe=true" height="100%" width="100%" frameborder="0"> </iframe>
    <script>
      window.addEventListener('message', function(e) {
        var iframe = document.getElementById('arenzaWidget');
        var eventName = e.data[0];
        var data = e.data[1];
        switch(eventName) {
        case 'setHeight':
          iframe.style.height = data + "px";
          break;
        }
      }, false);
    </script>
  </body>
</html>