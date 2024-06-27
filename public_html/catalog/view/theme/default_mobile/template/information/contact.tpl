<?php echo $header; ?>
    <div class="lc">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
    <?php } ?>
  </ul>
      <div class="contact">
        <h1>Контактная информация</h1>
        <div class="left">
          <div class="block">
            <div class="title">Офис, выставочный зал и производство:</div>
            <div>Московская область, Сергиево-Посадский район, город </div>
            <div>Сергиев Посад, Новоугличское шоссе, дом 67</div>
          </div>
          <div class="block">
            <div class="title">Бухгалтерия:</div>
            <div>Москва, Береговой проезд, дом 4, строение 1</div>
          </div>
          <div class="block">
            <div class="title">Режим работы:</div>
            <div>Понедельник – пятница с 9:00 до 18:00,</div>
            <div>суббота и воскресенье выходные дни</div>
            <div class="cmt">По предварительной договоренности мы доступны и в <br/>нерабочее время.</div>
          </div>
          <div class="block">
            <div class="title">Телефоны:</div>
            <div>8 (800) 775-73-49 (Бесплатные звонки по России)</div>
            <div>8 (495) 380-37-75</div>
            <div>8 (496) 551-34-72</div>
            <!--<div itemprop="telephone">+7 (717) 272-72-54 (для звонков из Казахстана)</div>-->
            <div itemprop="telephone">+7 (964) 727-02-80 (WhatsApp)</div>
            <div itemprop="telephone">+7 (964) 727-06-86 (WhatsApp)</div>
            <div itemprop="telephone">+7 (903) 544-49-28 (WhatsApp)</div>
           <!-- <div itemprop="telephone">+7 (365) 26 71 336 (для звонков из Крыма)</div>-->
          </div>
          <div class="block">
            <div class="title">Email:</div>
            <div>info@vend-shop.com</div>
          </div>
          <div class="block">
            <div class="title">Техническая поддержка:</div>
            <div>service@vend-shop.com"</div>
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
          
          <script src="https://www.google.com/recaptcha/api.js?render=6Lcn7DgpAAAAAOtz5NCMN3R4TUUc-JjHYSzKUCJ6"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('6Lcn7DgpAAAAAOtz5NCMN3R4TUUc-JjHYSzKUCJ6', { action: 'contact' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponseCont');
                recaptchaResponse.value = token;
            });
        });
    </script>
          
          <form data-template="request" data-subject="Форма связи" id="feedback">
            <input placeholder="Как вас зовут" name="name" value="<? echo $name ?>">
            <input required placeholder="Контактный телефон*" name="phone" value="<? echo $phone ?>">
            <input placeholder="Электронная почта" name="email" value="<?php echo $email ?>">
            <input type="text" name="region" required placeholder="Ваш регион">
            <div class="radios">
              <div>
                <input type="radio" checked="checked" name="firma" id="fiz" value="Физическое лицо"> <label for="fiz"> <span></span>Физическое лицо</label>
                
                <br>
              </div>
              <div>
                <input type="radio" name="firma" id="jur" value="Юридическое лицо"> <label for="jur"> <span></span>Юридическое лицо </label>
              </div>
            </div>
            <div class="radios has">
                <br>
              <div>
                <input type="radio" checked="checked" name="has" id="has_y" value="Да"> <label for="has_y"> <span></span>У меня есть автоматы</label>
              </div>

              <div>
                <input type="radio" name="has" id="has_no" value="Нет"> <label for="has_no"> <span></span>У меня нет автоматов </label>
                <br>
                <br>
              </div>
           </div>
           <div>
            <div class="amount">
              <input type="text" name="amount" required placeholder="Количество автоматов">
          </div>
              <input type="checkbox" name="credit" id="crdit"><label for="crdit"> Кредит/лизинг</label> 
          </div>
            <textarea placeholder="Какие автоматы интересуют?" name="note"><? echo $enquiry ?></textarea>
            <div class="prv">
            Нажимая на кнопку "отправить", вы даете согласие на обработку <a href="https://vend-shop.com/privacy/">персональных данных</a>.
          </div>
            <button type="submit" class="submit">Отправить заявку</button>
          <input type="hidden" name="url" value="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ?>">
			<input type="hidden" name="recaptcha_response" id="recaptchaResponseCont">
            
          </form>
        </div>
        <? echo $content_bottom; ?>
      </div>
    </div>
<?php echo $footer; ?>
