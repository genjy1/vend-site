<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
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
                <label class="col-sm-2 control-label" for="input-image"><? echo $text_image ?></label>
                <div class="col-sm-10">
                  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img  src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
          </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-description"><? echo $text_description ?></label>
                <div class="col-sm-10">
                  <textarea name="description" placeholder="<? echo $description_placeholder ?>" id="input-description" class="form-control"><? echo $description ?></textarea>
                </div>
          </div>

          <div class="form-group">
                <label class="col-sm-2 control-label" for="input-description">SEO</label>
                <div class="col-sm-10">
                  <input name="seo" class="form-control" value="<? echo $seo ?>">
                </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-description">Папка для группы изображений</label>
            <div class="col-sm-10">
              <select name="folder" class="form-control">
                <?php foreach($folders as $name => $folder) { ?>
                  <option value="<?php echo $folder ?>" <?php if($name == 'gall') {?> selected <?php } ?>><?php echo $name ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-description">Загрузить группу</label>
            <div class="col-sm-10">
            <input type="file" name="printfiles[]" multiple class="form-control">
            </div>
          </div>

              <div class="table-responsive">
                <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_additional_image; ?></td>
                      <td class="text-right"><?php echo $entry_sort_order; ?></td>
                      <td><?php echo $entry_caption; ?></td>
                      <td><?php echo $entry_value_videoid; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $image_row = 0; ?>
                    <?php foreach ($values as $value) { ?>
                    <tr id="image-row<?php echo $image_row; ?>">
                      <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $value['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="value[<?php echo $image_row; ?>][value]" value="<?php echo $value['value']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                      <td class="text-right"><input type="text" name="value[<?php echo $image_row; ?>][sort_order]" value="<?php echo $value['sort']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                      <td>
                        <input type="text" name="value[<?php echo $image_row; ?>][caption]" value="<?php echo $value['caption']; ?>" placeholder="<? echo $caption_placeholder ?>"  class="form-control">
                      </td>
                      <td>
                        <input type="text" name="value[<?php echo $image_row; ?>][videoid]" value="<?php echo $value['videoid']; ?>" class="form-control">
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
  html += '  <td class="text-right"><input type="text" name="value[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
  html += '<td><input type="text" name="value[' + image_row + '][caption]" value="" placeholder="<? echo $entry_caption ?>"  class="form-control"></td><td><input type="text" name="value[' + image_row + '][videoid]" value="" class="form-control"></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#images tbody').append(html);

  image_row++;
}
//--></script>

<?php echo $footer; ?>
