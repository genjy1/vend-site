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
<!--javascript:void(0)-->
          <div class="buttons"><a href="javascript:history.go(-2);" class="return">Вернуться в магазин</a>
            <div class="checkout"><a href="" id="nextstep">Дальше</a></div>
          </div>
        </div>
      </div>
    </div>
    <style>
    	.error{
    		border:1px #f00 solid;
    	}
    </style>
<script>
$("#nextstep").on('click', function(e){
e.preventDefault();
      	$('.cart').find('input').map(function(el){
      		
          name = $(this).attr('name');

          type = $(this).attr('type');

          value = $(this).val();

          required = $(this).attr('required');

          if(required !== undefined){
            if(value == ''){
              error = true;
              console.log(name + ' is required');
              $(this).addClass('error');
              return false;
            }else{
            	$(this).removeClass('error');	
            }
          }

          pattern = $(this).attr('pattern');
          if(pattern !== undefined){
            if(value.search(new RegExp(pattern,'i')) < 0){
              error = true;
              console.log('wrong pattern');
              return false;
            }
          }
          
          var value1 = $('[name=firstname]').val();
          var value2 = $('[name=telephone]').val();
          
if(value1 != '' && value2 != ''){
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
}            

        });

	

});
</script>
<?php echo $footer; ?>