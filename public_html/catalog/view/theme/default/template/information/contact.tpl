<?php echo $header; ?>
    <div class="lc">
    <ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
    <?php $i=1; foreach ($breadcrumbs as $breadcrumb) { ?>
      <li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem">
      <a href="<?php echo $breadcrumb['href']; ?>" itemprop="name"><?php echo $breadcrumb['text']; ?></a><span>/</span>
      <meta itemprop="position" content="<?=$i?>" />
      </li>
    <?php $i++; } ?>
  </ul>
      <div class="contact" itemscope itemtype="https://schema.org/Organization">
        <span itemprop="name" style="display: none">Vend-Shop</span>
        <h1>Контактная информация</h1>
        <div class="left">
          <div class="block" itemprop="address" itemscope itemtype="https://schema.org/PostalAddress">
            <div class="title">Офис, выставочный зал и производство:</div>
            <div>Московская область, Сергиево-Посадский район, город </div>
            <div><span itemprop="addressLocality">Сергиев Посад</span>, <span itemprop="streetAddress">Новоугличское шоссе, дом 67</span></div>
          </div>
          <div class="block">
            <div class="title">Режим работы:</div>
            <div>Понедельник – пятница с 9:00 до 18:00,</div>
            <div>суббота и воскресенье выходные дни</div>
            <div class="cmt">По предварительной договоренности мы доступны и в <br/>нерабочее время.</div>
          </div>
          <div class="block">
            <div class="title">Телефоны:</div>
            <div itemprop="telephone">8 (800) 775-73-49 (Бесплатные звонки по России)</div>
            <div itemprop="telephone">8 (495) 380-37-75</div>
            <div itemprop="telephone">8 (496) 551-34-72</div>
            <!--<div itemprop="telephone">+7 (717) 272-72-54 (для звонков из Казахстана)</div>-->
            <div itemprop="telephone">+7 (964) 727-02-80 (WhatsApp)</div>
            <div itemprop="telephone">+7 (964) 727-06-86 (WhatsApp)</div>
            <div itemprop="telephone">+7 (903) 544-49-28 (WhatsApp)</div>
           <!-- <div itemprop="telephone">+7 (365) 26 71 336 (для звонков из Крыма)</div>-->
          </div>
          <div class="block">
            <div class="title">Email:</div>
            <div itemprop="email">info@vend-shop.com</div>
          </div>
          <div class="block">
            <div class="title">Техническая поддержка:</div>
            <div itemprop="email">service@vend-shop.com</div>
          </div>
          <div class="block">
            <div class="title">Реквизиты:</div>
            <div>Полное наименование фирмы: ООО «Вендпром»</div>
            <div>ОГРН 1135042001940</div>
            <div>ИНН 5042127632</div>
            <div>КПП 504201001</div>
            <div>ОКПО 23507150</div>
          </div>
          <div class="block">
            <div class="title">Юридический адрес (в соответствии с учредительными документами) для правильного оформления отгрузочных документов:</div>
            <div>141301,Московская обл., Сергиево-Посадский р-н, г. Сергиев Посад, ш. Новоугличское, дом 67. Мы находимся на промышленной территории молокозавода, на проходной звоните 220.</div>
          </div>
          <div class="block">
            <div class="title">Платежные реквизиты:</div>
            <div>Р/с 40702810201390000502</div>
            <div>Филиал «Центральный» Банка ВТБ (ПАО) в г. Москве</div>
            <div>БИК 044525411</div>
            <div>К/с 30101810145250000411</div>
          </div>
          <div class="block">
            <div class="title">По вопросам рекламы и PR:</div>
            <div>8-800-775-73-49, добавочный 108, Татьяна Шубина</div>
            <div>pr@vend-shop.com</div>
          </div>
          <div class="block">
            <div class="title">Техническая поддержка:</div>
            <div>8-800-775-73-49, добавочный 211</div>
          </div>
          <div class="block">
            <div class="title">Генеральный директор:</div>
            <div>Водопьянов Михаил Алексеевич</div>
          </div>
        </div>
        <div class="right">
          <h2>Оставьте заявку! Мы свяжемся с Вами!</h2>
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
        <? echo $content_bottom; ?>
      </div>
    </div>
<?php echo $footer; ?>
