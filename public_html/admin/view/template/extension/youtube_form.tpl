<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-post" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-post" class="form-horizontal">
          
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="name" value="<?php echo isset($name) ? $name : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" required="required" />
                      
                    </div>
                  </div>
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_link; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="link" value="<?php echo isset($link) ? $link : ''; ?>" placeholder="<?php echo $entry_link; ?>" id="input-link" class="form-control" required="required" />
                    </div>
                  </div>
                  <div class="form-group required" style="display: none;">
                    <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="description" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control ckeditor"><?php echo isset($description) ? $description : ''; ?></textarea>
                      
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="category_ids"><span data-toggle="tooltip" title="">Показывать в категориях (оставить пустым для отображения во всех категориях)</span></label>
                    <div class="col-sm-10">
                      <input type="text" name="category" value="" placeholder="" id="input-category" class="form-control" />
                      <div id="category_ids" class="well well-sm" style="height: 150px; overflow: auto;">
                        <?php foreach ($category_ids as $category) { ?>
                          <div id="category<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
                          <input type="hidden" name="category_ids[]" value="<?php echo $category['category_id']; ?>" />
                          </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>


                  <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                <div class="col-sm-10">
                  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>
                
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
// $('#input-description<?php echo $language['language_id']; ?>').summernote({
//  height: 300
// });
<?php } ?>
//--></script> 
  <script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script></div>

<script>
  $(document).ready(function(){
$.expr[':'].regex = function(elem, index, match) {
    var matchParams = match[3].split(','),
        validLabels = /^(data|css):/,
        attr = {
            method: matchParams[0].match(validLabels) ? 
                        matchParams[0].split(':')[0] : 'attr',
            property: matchParams.shift().replace(validLabels,'')
        },
        regexFlags = 'ig',
        regex = new RegExp(matchParams.join('').replace(/^\s+|\s+$/g,''), regexFlags);
    return regex.test($(elem)[attr.method](attr.property));
}

    // $("input:regex(id,input-title\\d)").on("input", function(){
    $("input:regex(id,input-title\\d)").on("change", function(){

      text = $(this).val();
      // text = translit(text);
      id = $(this).attr('id');

      $("#input-meta-"+id).val(text);
      $("#input-meta-"+1).val(text);
      $("#input-meta-title"+1).val(text);
      $("#input-title"+1).val(text);
      $("input:regex(id,input-meta-title\\d)").map(function(e){
        if($(this).val() == ""){
          $(this).val(text);
        }
      });
    });

    function translit(str)
    {
      var ru=("А-а-Б-б-В-в-Ґ-ґ-Г-г-Д-д-Е-е-Ё-ё-Є-є-Ж-ж-З-з-И-и-І-і-Ї-ї-Й-й-К-к-Л-л-М-м-Н-н-О-о-П-п-Р-р-С-с-Т-т-У-у-Ф-ф-Х-х-Ц-ц-Ч-ч-Ш-ш-Щ-щ-Ъ-ъ-Ы-ы-Ь-ь-Э-э-Ю-ю-Я-я").split("-")
      var en=("A-a-B-b-V-v-G-g-G-g-D-d-E-e-E-e-E-e-ZH-zh-Z-z-I-i-I-i-I-i-J-j-K-k-L-l-M-m-N-n-O-o-P-p-R-r-S-s-T-t-U-u-F-f-H-h-TS-ts-CH-ch-SH-sh-SCH-sch-'-'-Y-y-'-'-E-e-YU-yu-YA-ya").split("-")
      var res = '';
      for(var i=0, l=str.length; i<l; i++)
      {
        var s = str.charAt(i), n = ru.indexOf(s);
        if(n >= 0) { res += en[n]; }
        else { res += s; }
        }
        return res;
    }


  });
</script>
<script type="text/javascript"><!--
CKEDITOR.config.language='<?php echo $ckeditorplus_language; ?>';
CKEDITOR.config.skin='<?php echo $ckeditorplus_skin; ?>';
CKEDITOR.config.height='<?php echo $ckeditorplus_height; ?>px';
CKEDITOR.on('dialogDefinition', function (event)
{
  var editor = event.editor;
  var dialogDefinition = event.data.definition;
  var dialogName = event.data.name;
  var tabCount = dialogDefinition.contents.length;
  for (var i = 0; i < tabCount; i++) {
    var browseButton = dialogDefinition.contents[i].get('browse');
    if (browseButton !== null) {
      browseButton.hidden = false;
      browseButton.onClick = function() {
        $('#modal-image').remove();
        var target = this.filebrowser.target;
        $.ajax({
          url: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
          dataType: 'html',
          success: function(html) {
            $('body').append('<div id="modal-image" class="modal cke_eval">' + html + '</div>');
            $('#modal-image').modal('show');
            $('body').on('click', '#modal-image a.thumbnail', function(e) {
              e.preventDefault();
              link = $(this).attr('href')
              dialog = CKEDITOR.dialog.getCurrent();
              targetval = target.split(":"); 
              dialog.setValueOf(targetval[0],targetval[1],link);
              $('#modal-image').modal('hide');
            });
          }
        });
      }
    }
  }
});

// Category
$('input[name=\'category\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
            value: item['category_id']
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'category\']').val('');

    $('#category_ids' + item['value']).remove();

    $('#category_ids').append('<div id="category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="category_ids[]" value="' + item['value'] + '" /></div>');
  }
});

$('#category_ids').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});


//--></script>


<?php echo $footer; ?>