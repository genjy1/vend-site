<?php echo $header; ?>
  <div class="lc">
    <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
      <?php } ?>
    </ul>
    <? $args = array(); ?>
          <?php foreach ($products as $product) { ?>
          <? $args[] = array(
              'MODEL' => $product['name'],
              'COUNT' => (float)$product['quantity'],
              'PRICE' => $product['total'],
          ); ?>
          <? } ?>
      <div class="cart">
        <h1>Корзина</h1>
        <form action="<? echo $action ?>" method='post'>
        <div class="cartform">
          <div class="head">
            <div class="col1">Фото</div>
            <div class="col2">Наименование товара</div>
            <div class="col3">Количество, шт</div>
            <div class="col4">
            <!-- Стоимость -->
            </div>
          </div>
          <?php foreach ($products as $product) { ?>
          <div class="item">
            <div class="col1"><img src="<? echo $product['thumb'] ?>"></div>
            <div class="col2">
              <div class="name"><? echo $product['name'] ?></div>
              <?php if ($product['options']) { ?>
              <div class="options">Опции:
              <?php foreach ($product['options'] as $option) { ?>
            <?php if ($option['type'] == 'radio') { ?>
            <div class="title"><?php echo $option['name']; ?></div>
            <?php foreach ($option['product_option_value'] as $option_value) { ?>
            <div>
              <input type="radio" id="r<?php echo $product['cart_id']; ?><?php echo $option_value['product_option_value_id']; ?>" name="option[<?php echo $product['cart_id']; ?>][<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" <? if(in_array( $option['product_option_id'] ."-" . $option_value['product_option_value_id'],$product['option_selected'])) { ?> checked <? } ?>>
              <label for="r<?php echo $product['cart_id']; ?><?php echo $option_value['product_option_value_id']; ?>"><span></span><?php echo $option_value['name']; ?>
              <? if($option_value['description'] != ''){ ?>
              <a class="inf"></a>
              <? } ?>
               <div class="popupopt">
                  <div><img src="<? echo $option_value['image'] ?>" alt=""></div>
                  <div><? echo $option_value['description'] ?></div>
                </div>

              </label>
              <?php if ($option_value['price']) { ?>
                    <!-- <div class="optprice"><?php echo $option_value['price']; ?></div> -->
            <? } ?>
            </div>
            <? } ?>
            <? } ?>
            <?php if ($option['type'] == 'checkbox') { ?>
            <div class="title"><?php echo $option['name']; ?></div>
            <?php foreach ($option['product_option_value'] as $option_value) { ?>
            <div>
              <input type="checkbox" id="c<?php echo $product['cart_id']; ?><?php echo $option_value['product_option_value_id']; ?>" name="option[<?php echo $product['cart_id']; ?>][<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" <? if(in_array( $option['product_option_id'] ."-" . $option_value['product_option_value_id'],$product['option_selected'])) { ?> checked <? } ?>>
              <label for="c<?php echo $product['cart_id']; ?><?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
              <? if($option_value['description'] != ''){ ?>
              <a class="inf"></a>
              <? } ?>
               <div class="popupopt">
                  <div><img src="<? echo $option_value['image'] ?>" alt=""></div>
                  <div><? echo $option_value['description'] ?></div>
                </div>

              </label>
              <?php if ($option_value['price']) { ?>
                    <div class="optprice">
                    <!-- <?php echo $option_value['price']; ?> -->
                    </div>
            <? } ?>
            </div>
            <? } ?>
            <? } ?>
              <? } ?>
              </div>
              <? } ?>
            </div>
            <div class="col3">
              <div class="qch">
                <a class="minus" onclick="minus(this)">- </a>
                  <input name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>">
                <a class="plus" onclick="plus(this)">+</a>
              </div>
            </div>
            <div class="col4">
              <div class="price">
              <!-- <?php echo $product['total']; ?> -->
              </div><a class="remove" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"></a>
            </div>
          </div>
          <? } ?>
          <div class="item">
           <!--  <div class="total">Итого: <span>
              <?php foreach ($totals as $total) { ?>
                <? if($total['code'] !='total') continue; ?>
                <? echo $total['text'] ?>
              <? } ?>
            </span></div> -->
          </div>
          <input type="hidden" name="credit" value="0">
          <div class="buttons"><a href="javascript:history.back();">Вернуться в магазин</a>
            <div class="checkout"><a onclick='retailcreditdialog(<? echo json_encode($args); ?>,2);' href="javascript:void(0);">Оформить кредит</a>
            <a href="<? echo $checkout ?>">Оформить заказ</a></div>
          </div>
        </div>
        </form>
      </div>
    </div>
<script>
  $('.options input').on("change", function(){
    $('.cart form').submit();
  });
  function plus(el){
    $(el).prev('input').val(parseInt($(el).prev('input').val()) + 1);
    $(el).closest('form').submit();
  }
  function minus(el){
    val = parseInt($(el).next('input').val()) - 1;
    if(val < 1){
      val = 1;
    }
    $(el).next('input').val(val);
    $(el).closest('form').submit();
  }
</script>
<script type="text/javascript">
  $(document).ready(function(){

  $('.inf').on('mouseover', function(e){
    x = e.clientX + 4;
    y = e.clientY;
    $(this).next().show();
    $(this).next().css({"left":x,"top":y});
  }).on('mouseout', function(){
    $(this).next().hide();
  });
});
</script>
<?php echo $footer; ?> 