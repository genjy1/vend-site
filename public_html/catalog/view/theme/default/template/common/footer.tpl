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
    <?php echo $feedback; ?>

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
      <a href="javascript:void(0)" class="close" id="pomoschnet" onclick="ym(22761283, 'reachGoal', 'pomoschnet'); return true;">Спасибо, я сам разберусь</a>
    </div>

    <div class="showHelp" id="pomoschviz" onclick="ym(22761283, 'reachGoal', 'pomoschviz'); return true;"></div>

    <?php echo $scripts; ?>

</body>
</html>