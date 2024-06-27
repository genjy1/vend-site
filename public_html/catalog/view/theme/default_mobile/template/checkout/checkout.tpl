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
          <div class="field l">
            <label>Имя<span class="req">*</span></label>
            <input name="firstname"  type="text" required="required">
          </div>
          <div class="field r"  type="text">
            <label>Телефон<span class="req">*</span></label>
            <input name="telephone"  type="text" required="required">
          </div>
          <div class="field r">
            <label>Email</label>
            <input name="email"  type="text">
          </div>

          <div class="buttons"><a href="javascript:history.back(-2);" class="return">Вернуться в магазин</a>
            <div class="checkout"><a href="javascript:void(0)" id="nextstep">Дальше</a></div>
          </div>
        </div>
      </div>
    </div>
<script>
$("#nextstep").on('click', function(){
  $.ajax({
     url: 'index.php?route=checkout/confirm',
     type: 'post',
     data: $('.cart input[type=\'text\']'),
     dataType: 'json',
     success: function(json) {
       console.log(json)
       if (json['redirect']) {
          location = json['redirect'];
       }
     },
     error: function(xhr, ajaxOptions, thrownError) {
       alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
     }
   });
});
</script>
<?php echo $footer; ?>