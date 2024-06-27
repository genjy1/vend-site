<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-post" data-toggle="tooltip" title="Save" class="btn btn-primary save"><i class="fa fa-save"></i></button>
    </div>

      <h1>Банер для online.vend-shop.com</h1>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error) { ?>
        <?php foreach($error as $er){ ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $er; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">

        <h3 class="panel-title"><i class="fa fa-pencil"></i>Settings</h3>

      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-post" class="form-horizontal">
          <div class="tab-content">
            <div class="tab-pane active" id="tab-push">

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-image">Сменить изображение (330 x 120)</label>
                    <div class="col-sm-10">
                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img  src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-link">Ссылка</label>
                    <div class="col-sm-10">
                        <input type="text" name="link" value="<?php echo $link ?>" id="input-name" class="form-control">
                    </div>
                </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>


</div>

 <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
  html  = '<tr id="image-row' + image_row + '">';
  html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="value[' + image_row + '][value]" value="" id="input-image' + image_row + '" /></td>';
  html += '  <td class="text-right"><input type="text" name="value[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
  html += '<td><input type="text" name="value[' + image_row + '][caption]" value="" placeholder="<? echo $entry_caption ?>"  class="form-control"></td><td><input type="text" name="value[' + image_row + '][videoid]" value="" class="form-control"></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#images tbody').append(html);

  image_row++;
}
//--></script>


<?php echo $footer; ?>