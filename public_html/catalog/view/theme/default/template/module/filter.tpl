<? if($isfilter){ ?>
      <div class="filter" id="realize">
        <div class="filterinner">
        <div><div class="it">Товар для реализации<a class="op"></a></div>
            <div class="fwrap">
            <? foreach($categories as $category) { ?>
                <div onmouseover = "void(0)" onmouseout  = "void(0)" onclick = "void(0)" style="cursor: pointer;" class="filteritem" data-group="<? echo $category['category_id'] ?>"><a href="<? echo $category['href'] ?>"><? echo $category['name'] ?></a></div>
            <? } ?>
            </div>
        </div>
        <? if($type){ ?>
        <? } ?>
        <?php if($showprice) { ?>
        <div class="price">
        <label for="">Стоимость</label>
            <div class="slider">
                <input id="sp" type="text" value="" data-slider-min="<? echo $min ?>" data-slider-max="<? echo $max ?>" data-slider-step="1000" data-slider-value="[<? echo $start ?>,<? echo $end ?>]" data-slider-tooltip="hide"/>
                <div class="from">от <span><? echo (int)$start ?></span> ₽</div>
                <div class="to">до <span><? echo (int)$end ?></span> ₽</div>
                <input type="hidden" name="from" value="<? echo (int)$start ?>">
                <input type="hidden" name="to" value="<? echo (int)$end ?>">
            </div>
        </div>
        <? } ?>
            <button type="reset" class="reset">Сбросить</button>
        </div>
      </div>

<script>
  $(document).ready(function(){

    $('.reset').on("click", function(){
        location = '<?php echo $action; ?>';
    });

    var filter = "<? echo $filtered ?>";
    var price_filter = "<? echo $price_filter ?>";
    <?php if($showprice) { ?>
    var slider = $("#sp").bootstrapSlider();

    slider.on("slide", function(slideEvt) {
        value = slideEvt.value;
        value = value.slice(",");
        $("input[name='from']").val(value[0]);
        $("input[name='to']").val(value[1]);

        $(".from span").text(value[0]);
        $(".to span").text(value[1]);
    });

    slider.on("slideStop", function(slideEvt) {

        value = slideEvt.value;
        value = value.slice(",");
        $("input[name='from']").val(value[0]);
        $("input[name='to']").val(value[1]);

        $(".from span").text(value[0]);
        $(".to span").text(value[1]);

        
        if(filter != ''){
            location = '<?php echo $action; ?>&' + filter + '&price_filter' + $("input[name='from']").val() +"_" + $("input[name='to']").val();
        } else {
            location = '<?php echo $action; ?>&price_filter=' + $("input[name='from']").val() +"_" + $("input[name='to']").val();
        }
    });
    <? } ?>
    $(".fwrap .filteritem").on("click", function(){
        href = $(this).find("a").attr("href");
        location.href = href;
    });

    $(".filter .it").on("click", function(){
        $(this).toggleClass("openf").parent().find('.filteritem').toggle();
    });


    $(document).on("touchstart", "#type > div.filteritem" , function(){
        filter_id = $(this).data("atr");
        if(price_filter != ''){
            // location = '<?php echo $action; ?>&filter=' + filter_id + '&' + price_filter;
        } else {
            // location = '<?php echo $action; ?>&filter=' + filter_id;
        }
    });

  });
</script>
<? } ?>