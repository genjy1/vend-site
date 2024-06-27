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
                <label class="col-sm-2 control-label" for="input-description"><? echo $text_description ?></label>
                <div class="col-sm-10">
                  <textarea name="description" placeholder="<? echo $description_placeholder ?>" id="input-description" class="form-control"><? echo $description ?></textarea>
                </div>
          </div>

              <div class="table-responsive">
                <table id="images" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_additional_image; ?></td>
                      <td class="text-right"><?php echo $entry_sort_order; ?></td>
                      <td><?php echo $entry_name; ?></td>
                      <td><?php echo $entry_value_description; ?></td>
                      <td></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $image_row = 0; ?>
                    <?php foreach ($values as $value) { ?>
                    <tr id="image-row<?php echo $image_row; ?>">
                      <input type="hidden" name="position[<?php echo $image_row; ?>][id]" value="<?php echo $value['id']; ?>" />
                      <td class="text-left"><a href="" id="thumb-image<?php echo $image_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $value['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="position[<?php echo $image_row; ?>][image]" value="<?php echo $value['image']; ?>" id="input-image<?php echo $image_row; ?>" /></td>
                      <td class="text-right"><input type="text" name="position[<?php echo $image_row; ?>][sort_order]" value="<?php echo $value['sort']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>
                      <td>
                        <input type="text" name="position[<?php echo $image_row; ?>][name]" value="<?php echo $value['name']; ?>" placeholder="<? echo $name_placeholder ?>"  class="form-control">
                      </td>
                      <td>
                        <input type="text" name="position[<?php echo $image_row; ?>][description]" value="<?php echo $value['description']; ?>" placeholder="<? echo $name_placeholder ?>"  class="form-control">
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
<?php foreach ($values as $value) { ?>
          <div class="form-group">
            <div class="col-sm-4">
              <img src="<?php echo $value['bigimage']; ?>" alt="" title="" />
            </div>
            <div class="col-sm-8">
              <div class="form-group">
                <div class="col-sm-3">
                  <label>title</label>
                </div>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="titles[]">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <label>product</label>
                </div>
                <div class="col-sm-9">
                  <input type="text" class="form-control" name="productset[]" value="">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-3">
                  <label>tools</label>
                </div>
                <div class="col-sm-9">
                </div>
              </div>
            </div>
          </div>
<? } ?>

          </div>
        </form>
      </div>
    </div>
  </div>

 <script type="text/javascript"><!--
var image_row = <?php echo $image_row; ?>;

function addImage() {
  html  = '<tr id="image-row' + image_row + '">';
  html += '  <td class="text-left"><input type="hidden" name="position[' + image_row + '][id]" value="ins" /><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="position[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
  html += '  <td class="text-right"><input type="text" name="position[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
  html += '<td><input type="text" name="position[' + image_row + '][name]" value="" placeholder="<? echo $entry_name ?>"  class="form-control"></td><td><input type="text" name="position[' + image_row + '][description]" value="" placeholder="<? echo $text_description ?>"  class="form-control"></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';

  $('#images tbody').append(html);

  image_row++;
}


// Related
$('input[name=\'productset[]\']').autocomplete({
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
    $(this).val(item['label']);
    $(this).parent().find('input[type="hidden"]').remove();
    $(this).parent().append('<input type="hidden" name="product_set[]" value="' + item['value'] + '" />');  
  } 
});


//--></script>


<?php echo $footer; ?>
