<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<style>
  .item{
    width: 360px;
  }
  .item input{
    float: left;
    width: 270px;
  }
  .item button{
    float: left;
  }
  .img-thumbnail{
    position: relative;
  }
  .mappoint{

  }
  .mappoint img{
  	position: absolute;
  }
  .mappoint span{
    position: absolute;
    left: 32px;
    font-size: 16px;
    color: #000;
    top: 9px;
  }
  .num{
	width: 35px!important;
	height: 35px;
	margin: 0 4px;
	border: 1px solid #ccc;
	text-align: center;
	border-radius: 3px;
  }
  .pins{
    margin-bottom: 50px;
    font-size: 15px;
    border-radius: 3px;
    height: 40px;
    padding: 0 10px;
  }
</style>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
          <div class="tab-content">

            <div style="text-align: center;font-weight: bold;margin: 15px 0">
              При добавлении нового изображения добавление позиций возможно после сохранения
            </div>


              <div class="table-responsive">
                <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_additional_image; ?></td>
                      <td class="text-right"><?php echo $entry_sort_order; ?></td>
                      <td>Товары</td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $image_row = 0; ?>
                    <?php $num = 0; ?>
                    <?php $num1 = 0; ?>
                    <?php foreach ($maps as $value) { ?>
                    <tr id="image-row<?php echo $image_row; ?>" data-map="<? echo $value['map_id'] ?>">
                      <td class="text-center">
                        <div class="pin">
                          <select name="map[<? echo $value['map_id'] ?>][pins]" class="pins">
                            <option value="1" <?php if($value['pins'] == 1) {?> selected="selected"<?php } ?>>Отображать метки</option>
                            <option value="0" <?php if($value['pins'] == 0) {?> selected="selected"<?php } ?>>Не отображать метки</option>
                          </select>
                        </div>
                        <a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail">

                        <? foreach($value['items'] as $item) {?>
                        <?php $num++; ?>
                          <? $coords = explode(",",$item['coords']); ?>
                          <div id="<? echo $value['map_id'] ?>_<? echo $item['product_id'] ?>" class="mappoint" style="position:absolute;left:<? echo $coords[0] ?>px;top:<? echo ($coords[1] - 28) ?>px"><img src="/image/mappoint.png"><span><? echo $num ?></span></div>
                        <? } ?>


                      <img src="<?php echo $value['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="map[<? echo $value['map_id'] ?>][image]" value="<?php echo $value['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                      <td class="text-right"><input type="text" name="map[<? echo $value['map_id'] ?>][sort_order]" value="<?php echo $value['sort_order']; ?>" placeholder="0" class="form-control" /></td>
                      <td class="itemlist">
                        <? foreach($value['items'] as $item) {?>
						<?php $num1++; ?>
                        <div class="item" data-mapid="<? echo $value['map_id'] ?>_<? echo $item['product_id'] ?>"><input type="text" value="<? echo $item['name'] ?>" class="form-control"><input type="hidden" name="items[<? echo $value['map_id'] ?>][products][]" value="<? echo $item['product_id'] ?>"> <input type="hidden" name="items[<? echo $value['map_id'] ?>][coords][]" value="<? echo $item['coords'] ?>">
                         <input type="text" value="<? echo $item['num'] ?>" class="num" name="num[]">
                         <button type="button" data-toggle="tooltip" class="btn btn-danger rem"><i class="fa fa-minus-circle"></i></button></div>


                        <? } ?>
                      </td>
                      <td class="text-left"><button type="button" onclick="$('#image-row<?php echo $image_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $image_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td colspan="2"></td>
                      <td></td>
                      <td class="text-left"><button type="button" onclick="addImage();" data-toggle="tooltip" title="<?php echo $button_image_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<div id="selectprod" style="display: none; z-index: 9; position: fixed;top: 100px;left: 50%;padding: 30px;background: #fff;width: 600px;margin-left: -300px;height: 300px;padding-top: 103px;border: 3px solid #777;
">
  <div class="text" style="margin-bottom: 20px;text-align: center;font-weight: bold;">Начните вводить название товара</div>
  <div>
    <input type="text" name="getproduct" class="form-control">
  </div>
</div>

 <script type="text/javascript"><!--

 var items = [];
 var el = null;
 var map_id = 0;
 var relativeX = 0;
 var relativeY = 0;

$("#images img").on("click", function(e){

  var offset = $(this).offset();
  relativeX = (e.pageX - offset.left);
  relativeY = (e.pageY - offset.top);
 
  // alert("X: " + relativeX + "  Y: " + relativeY);

  map_id = $(this).closest('tr').data('map');

 el = $(this).closest('tr');
$("#selectprod").show();
  // if(inar(map_id)){
  //   items[map_id] = items[map_id] + 1;
  // } else {
  //   items[map_id] = 1;
  // }

});


function inar(index){
for(var i=0;i<items.length;i++){
  if(index==mass[i]){return true}
  }
return false;
}


var image_row = <?php echo $image_row; ?>;

function addImage() {
  html  = '<tr id="image-row' + image_row + '">';
  html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="map[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
  html += '  <td class="text-right"><input type="text" name="map[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
  html += '<td><input type="text" name="map[' + image_row + '][products]" value=""  class="form-control" disabled></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#images tbody').append(html);

  image_row++;
}


num = <? echo $num; ?>;
num1 = <? echo $num1; ?>;

$('input[name=\'getproduct\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
  	num = num + 1;
    num1 = num1 + 1;
    // lastitem = item;
    style = "block";

    if(el.find(".pins").val() == 0){
      style = "none";
    }
    el.find('.img-thumbnail').append('<div id="'+map_id+'_'+item['value']+'" class="mappoint" style="display:' + style + ';position:absolute;left:'+relativeX+'px;top:'+(relativeY-28)+'px"><img src="/image/mappoint.png"><span>'+num+'</span></div>');

    el.find(".itemlist").append('<div class="item" data-mapid="'+map_id+'_'+item['value']+'"><input type="text" value="'+item['label']+'" class="form-control"><input type="hidden" name="items['+map_id+'][products][]" value="'+item['value']+'"> <input type="hidden" name="items['+map_id+'][coords][]" value="'+relativeX+','+relativeY+'"><input type="text" value="'+num1 +'" class="num" name="num[]"> <button type="button" data-toggle="tooltip" class="btn btn-danger rem"><i class="fa fa-minus-circle"></i></button></div>');
    $("#selectprod").hide();
  }
});

$('body').on("click", ".rem", function(){
  mapid = $(this).parent().data("mapid");
  $("#"+mapid).remove();
  $(this).parent().remove();
});




$(document).ready(function(){
  $("html").on("click", ".popover-content",  function(){
    $(".mappoint").hide();
    $("#selectprod").hide();
  });

  $(".pins").map(function(){
    if($(this).val() == 0){
      $(this).parent().parent().find(".mappoint").hide();
    }
  });

  $(".pins").on("change", function(){
    if($(this).val() == 0){
      $(this).parent().parent().find(".mappoint").hide();
    } else {
      $(this).parent().parent().find(".mappoint").show();
    }
  })

});

//--></script>

<?php echo $footer; ?>


