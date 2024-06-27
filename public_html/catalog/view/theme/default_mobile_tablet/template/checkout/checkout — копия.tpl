<?php echo $header; ?>
    <div class="lc">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
      </ul>
      <div class="cart">
        <h1>Контактная информация</h1>
        <div class="checkout">
          <div class="customer">
            <input type="radio" id="r1" name="customer" value="0" checked>
            <label for="r1"> <span> </span><span>Я новый покупатель</span></label>
            <input type="radio" id="r2" name="customer" value="1">
            <label for="r2"> <span></span><span>Я уже заказывал ранее и у меня есть аккаунт</span></label>
          </div>
          <div class="field">
            <label>Имя<span class="req">*</span></label>
            <input name="name">
          </div>
          <div class="field">
            <label>Телефон<span class="req">*</span></label>
            <input name="phone">
          </div>
          <div class="field">
            <label>Фамилия<span class="req">*</span></label>
            <input name="surname">
          </div>
          <div class="field">
            <label>Email<span class="req">*</span></label>
            <input name="email">
          </div>
          <div class="field">
            <label>Отчество<span class="req">*</span></label>
            <input name="part">
          </div>
          <div class="customer custperson">
            <input type="radio" id="r3" name="type" checked value="0">
            <label for="r1"> <span> </span><span>Физическое лицо</span></label>
            <input type="radio" id="r4" name="type" value="1">
            <label for="r2"> <span></span><span>Юридическое лицо</span></label>
          </div>
          <div class="field">
            <label>Серия, № паспорта<span class="req">*</span></label>
            <input name="passport">
          </div>

          <div class="field">
            <label>Когда выдан<span class="req">*</span></label>
            <input name="passdate">
          </div>
          <div class="field">
            <label>Кем выдан паспорт<span class="req">*</span></label>
            <input name="passserv">
          </div>
          <div class="addr">Адрес доставки</div>
          <div class="field">
            <label>Индекс</label>
            <input name="zip">
          </div>
          <div class="field">
            <label>Страна</label>
            <input name="country">
          </div>
          <div class="field">
            <label>Город</label>
            <input name="city">
          </div>
          <div class="field">
            <label>Регион</label>
            <input name="region">
          </div>
          <div class="field">
            <label>Улица, дом, квартира</label>
            <input name="adrres">
          </div>
          <div class="buttons"><a class="return">Вернуться в магазин</a>
            <div class="checkout"><a>Дальше</a></div>
          </div>
        </div>
      </div>
    </div>
<script>
  $(document).ready(function() {
    $('input[name="customer"').on("change", function(){
      val = $(this).val();
      if(val > 0){
        $('.field, .addr, .custperson').fadeOut();
      } else {
        $('.field, .addr, .custperson').fadeIn();
      }
    });
  })
</script>
<?php echo $footer; ?>