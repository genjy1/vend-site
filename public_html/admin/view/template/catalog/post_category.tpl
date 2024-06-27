<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-post" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $category_title; ?></h1>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-post" class="form-horizontal">
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="tab-content">
          <table id="category-value" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left required"><?php echo $entry_category; ?></td>
                <td class="text-left"><?php echo $entry_image; ?></td>
                <td class="text-right"><?php echo $entry_sort_order; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $category_row = 0; ?>
              <?php foreach ($categories as $category) { ?>
              <tr id="category-value-row<?php echo $category_row; ?>">
                <td class="text-left"><input type="hidden" name="category[<?php echo $category_row; ?>][category_id]" value="<?php echo $category['category_id']; ?>" />
                  <?php foreach ($languages as $language) { ?>
                  <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="category[<?php echo $category_row; ?>][category_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($category['category_description'][$language['language_id']]) ? $category['category_description'][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_category; ?>" class="form-control" />
                  </div>
                  <?php if (isset($error_category[$category_row][$language['language_id']])) { ?>
                  <div class="text-danger"><?php echo $error_category[$category_row][$language['language_id']]; ?></div>
                  <?php } ?>
                  <?php } ?></td>
                <td class="text-left"><a href="" id="thumb-image<?php echo $category_row; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo $category['thumb']; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="category[<?php echo $category_row; ?>][image]" value="<?php echo $category['image']; ?>" id="input-image<?php echo $category_row; ?>" /></td>
                <td class="text-right"><input type="text" name="category[<?php echo $category_row; ?>][sort_order]" value="<?php echo $category['sort_order']; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#category-value-row<?php echo $category_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $text_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $category_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3"></td>
                <td class="text-left"><button type="button" onclick="addcategoryValue();" data-toggle="tooltip" title="<?php echo $text_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>

              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>


  <script type="text/javascript"><!--

var category_row = <?php echo $category_row; ?>;

function addcategoryValue() {
  html  = '<tr id="category-value-row' + category_row + '">'; 
    html += '  <td class="text-left"><input type="hidden" name="category[' + category_row + '][category_id]" value="0" />';
  <?php foreach ($languages as $language) { ?>
  html += '    <div class="input-group">';
  html += '      <span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span><input type="text" name="category[' + category_row + '][category_description][<?php echo $language['language_id']; ?>][name]" value="" placeholder="<?php echo $entry_category; ?>" class="form-control" />';
    html += '    </div>';
  <?php } ?>
  html += '  </td>';
    html += '  <td class="text-left"><a href="" id="thumb-image' + category_row + '" data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="category[' + category_row + '][image]" value="" id="input-image' + category_row + '" /></td>';
  html += '  <td class="text-right"><input type="text" name="category[' + category_row + '][sort_order]" value="" placeholder="" class="form-control" /></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#category-value-row' + category_row + '\').remove();" data-toggle="tooltip" title="<?php echo $text_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';  
  
  $('#category-value tbody').append(html);
  
  category_row++;
}
//--></script>

<?php echo $footer; ?>