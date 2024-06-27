<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
<style>
.input-group{
  max-width: 300px;
}
.addl{
  text-align: center;
  margin-top: 5px;
}
.img-backgnail{
padding: 4px;
display: block;
background: #1978ab;
}
.img-thumbnail{
padding: 4px;
display: block;
background: #1978ab;
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
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name"><? echo $text_name ?></label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="<? echo $name ?>" placeholder="<? echo $name_placeholder ?>" id="input-name" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-name">Эффект</label>
                <div class="col-sm-10">
                  <select name="effect" id="" class="form-control">
                    <option value="0" <?if($effect == 0){?>selecteed<?}?>>Стандарт</option>
                    <option value="1" <?if($effect == 1){?>selected<?}?>>Вертикальный</option>
                    <option value="2" <?if($effect == 2){?>selected<?}?>>Переход</option>
                  </select>

                </div>
            </div>



              <div class="table-responsive" style="overflow-x: scroll">
                <table id="images" class="table table-striped table-bordered table-hover" style="width: 1800px">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_additional_image; ?></td>
                      <td class="text-right"><?php echo $entry_sort_order; ?></td>
                      <td class="text-left">Фон</td>
                      <td class="text-left">Текст ссылки</td>
                      <td><?php echo $entry_caption; ?></td>
                      <td>Цвет заголовка</td>
                      <td>Цвет кнопки</td>
                      <td>Текст</td>
                      <td>Цвет текста</td>
                      <td>Таймер</td>
                      <td>Ссылки</td>
                      <td>Рассположение</td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $image_row = 0; ?>
                    <?php foreach ($values as $value) { ?>
                    <tr id="image-row<?php echo $image_row; ?>">
                      <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $value['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="value[<?php echo $image_row; ?>][value]" value="<?php echo $value['value']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                      <td class="text-right"><input type="text" name="value[<?php echo $image_row; ?>][sort_order]" value="<?php echo $value['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>

                      <td class="text-left"><a href="" id="backg-image<?php echo $image_row; ?>" data-toggle="image" class="img-backgnail"><img src="<?php echo $value['backg']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="value[<?php echo $image_row; ?>][backgimage]" value="<?php echo $value['backgimage']; ?>" id="input-backgimage<?php echo $image_row; ?>" /></td>
                      <td>
                      <?php foreach ($languages as $language) { ?>
                          <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="value[<?php echo $image_row; ?>][description][<?php echo $language['language_id']; ?>][text_link]" value="<? echo $value['description'][$language['language_id']]['text_link'] ?>" placeholder="" class="form-control"></div>
                      <? } ?>
                      </td>
                      <td>
                      <?php foreach ($languages as $language) { ?>
                          <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="value[<?php echo $image_row; ?>][description][<?php echo $language['language_id']; ?>][caption]" value="<? echo $value['description'][$language['language_id']]['caption'] ?>" placeholder="" class="form-control"></div>
                      <? } ?>
                      </td>
                      <td>
                        <input type="color" class="form-control" name="value[<?php echo $image_row; ?>][color_caption]" value="<? echo $value['color_caption'] ?>">
                      </td>
                      <td>
                        <input type="color" class="form-control" name="value[<?php echo $image_row; ?>][color_button]" value="<? echo $value['color_button'] ?>">
                      </td>
                      <td>
                      <?php foreach ($languages as $language) { ?>
                        <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="value[<?php echo $image_row; ?>][description][<?php echo $language['language_id']; ?>][text]" value="<? echo $value['description'][$language['language_id']]['text'] ?>" placeholder=""  class="form-control"></div>
                     <? } ?>
                      </td>
                      <td>
                        <input type="color" class="form-control" name="value[<?php echo $image_row; ?>][color_text]" value="<? echo $value['color_text'] ?>">
                      </td>
                      <td>
                        <input type="text" class="form-control date<? echo $image_row ?>" name="value[<?php echo $image_row; ?>][timer]" value="<? echo $value['timer'] ?>">
                        <script>
                          pickmeup('.date<? echo $image_row ?>', { position : 'bottom', hide_on_select : true , default_date : false, fomat : 'Y-m-d'});
                        </script>
                      </td>
                      <td>
                        <? foreach($value['links'] as $link){ ?>
                          <input type="text" name="value[<?php echo $image_row; ?>][links][]" value="<? echo $link ?>" placeholder=""  class="form-control">
                        <? } ?>
                        <div class="addl">
                          <button class="btn btn-primary">add</button>
                        </div>

                        </td>
                      <td>
                        <select name="value[<?php echo $image_row; ?>][position]" id="" class="form-control">
                          <option value="0" <?if($value['position'] == 0){?>selected<?}?>>Слева</option>
                          <option value="1" <?if($value['position'] == 1){?>selected<?}?>>Справа</option>
                        </select>
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
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
                      <td></td>
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

 <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
  html  = '<tr id="image-row' + image_row + '">';
  html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="value[' + image_row + '][value]" value="" id="input-image' + image_row + '" /></td>';
  html += '  <td class="text-right"><input type="text" name="value[' + image_row + '][sort_order]" value="0" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
  html += '<td class="text-left"><a href="" id="backg-image<?php echo $image_row; ?>" data-toggle="image" class="img-backgnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="value[' + image_row + '][backgimage]" value="<?php echo $value['backgimage']; ?>" id="input-backgimage<?php echo $image_row; ?>" /></td>';
  html += '<td><?php foreach ($languages as $language) { ?><div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="value[' + image_row + '][description][<?php echo $language['language_id']; ?>][text_link]" value="" placeholder="" class="form-control"></div><? } ?></td>';
  html += '<td>';
  <?php foreach ($languages as $language) { ?>
  html += '<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="value[' + image_row + '][description][<?php echo $language['language_id']; ?>][caption]" value="" placeholder=""  class="form-control"></div>';
<? } ?>
  html += '</td>';

  html += '<td><input type="color" name="value[' + image_row + '][color_caption]" value="#ffffff" placeholder=""  class="form-control"></td>';

  html += '<td><input type="color" name="value[' + image_row + '][color_button]" value="#ffffff" placeholder=""  class="form-control"></td>';

  html += '<td>';
  <?php foreach ($languages as $language) { ?>
  html += '<div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="value[' + image_row + '][description][<?php echo $language['language_id']; ?>][text]" value="" placeholder=""  class="form-control"></div>';
  <? } ?>
  html += '</td>';

  html += '<td><input type="color" name="value[' + image_row + '][color_text]" value="#ffffff" placeholder=""  class="form-control"></td>';

html += '<td><input type="text" class="form-control date' + image_row + '" name="value[' + image_row + '][timer]" value=""></td>';



    html += '<td>';

    html += '<input type="text" name="value[' + image_row + '][links][]" value="" placeholder=""  class="form-control"><div class="addl"><button class="btn btn-primary">add</button></div></td>';


    html += '<td> <select name="value[' + image_row + '][position]" id="" class="form-control"><option value="0">Слева</option><option value="1">Справа</option></select></td>';


  html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#images tbody').append(html);

  image_row++;
}

$(document).ready(function(){
  $("body").on("click", ".addl button", function(e){
    e.preventDefault();
    $(this).parent().prev().after('<input type="text" name="value[<?php echo $image_row; ?>][links][]" value="" placeholder=""  class="form-control">');
  });
  // pickmeup('.date', { position : 'bottom', hide_on_select : true , default_date : false, fomat : 'Y-m-d'});
});


//--></script>

<?php echo $footer; ?>
