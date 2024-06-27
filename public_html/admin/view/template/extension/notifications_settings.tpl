<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-post" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary save"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>

      <h1>Settings</h1>

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

        <h3 class="panel-title"><i class="fa fa-pencil"></i>Settings</h3>

      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-post" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab-push" data-toggle="tab">Settings </a>
            </li>
            <li>
              <a href="#tab-topics" data-toggle="tab">Topics </a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-push">

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name">Server key</label>
                <div class="col-sm-10">
                  <input type="text" name="notification_key" value="<?php echo isset($key) ? $key : ''; ?>" placeholder="Server key" id="input-name" class="form-control" required="required" />
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name">Config Firebase</label>
                <div class="col-sm-10">
                  <textarea name="notification_config" placeholder="Put firebase here" class="form-control" required="required"><?php echo isset($config) ? $config : ''; ?></textarea>
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-url">Default click action (optional)</label>
                <div class="col-sm-10">
                  <input type="text" name="notification_click_action" value="<?php echo isset($click_action) ? $click_action : ''; ?>" placeholder="Click action" id="input-url" class="form-control"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image">Default icon</label>
                <div class="col-sm-10">
                  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="notification_image" value="<?php echo $image; ?>" id="input-image" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                  <button type="button" class="btn btn-primary save">Save</button>
                </div>
              </div>
                
            </div>

            <div class="tab-pane" id="tab-topics">

              <table id="topic-value" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left required">Label</td>
                <td class="text-left required">Topic</td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $topic_value_row = 0; ?>
              <?php foreach ($topic_values as $topic_value) { ?>
              <tr id="topic-value-row<?php echo $topic_value_row; ?>">
                <td class="text-left">
                  <input type="hidden" name="topic_value[<?php echo $topic_value_row; ?>][id]" value="<?php echo $topic_value['id']; ?>">
                  <input type="text" name="topic_value[<?php echo $topic_value_row; ?>][label]" value="<?php echo $topic_value['label']; ?>" class="form-control" id="input-label<?php echo $topic_value_row; ?>" /></td>
                <td class="text-right">
                  <input type="text" readonly="readonly" name="topic_value[<?php echo $topic_value_row; ?>][topic]" value="<?php echo $topic_value['topic']; ?>" class="form-control" /></td>
                <td class="text-left"><button type="button" onclick="$('#topic-value-row<?php echo $topic_value_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $topic_value_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="2"></td>
                <td class="text-left"><button type="button" onclick="addTopicValue();" data-toggle="tooltip" title="Add" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>

              <div class="form-group">
                <div class="col-sm-10">
                  <button type="button" class="btn btn-primary addTopic">Save</button>
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


$(".addTopic").on("click", function(e){
  e.preventDefault();

  $.ajax({
    url: "index.php?route=extension/notifications/saveTopics&token=<?php echo $token; ?>",
    type: 'post',
    data: $('#content select, #content input, #content textarea'),
    dataType: 'json',
    beforeSend: function() {
      $('#button-send').button('loading');
    },
    complete: function() {
      $('#button-send').button('reset');
    },
    success: function(json) {
      $('.alert, .text-danger').remove();

      if (json['error']) {
        if (json['error']['warning']) {
          $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
        }

        if (json['error']['subject']) {
          $('input[name=\'subject\']').after('<div class="text-danger">' + json['error']['subject'] + '</div>');
        }

        if (json['error']['message']) {
          $('textarea[name=\'body\']').parent().append('<div class="text-danger">' + json['error']['message'] + '</div>');
        }
      }

      if (json['success']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
      }
    }
  });
});

$(".save").on("click", function(e){
    e.preventDefault();

    $.ajax({
    url: "index.php?route=extension/notifications/saveSettings&token=<?php echo $token; ?>",
    type: 'post',
    data: $('#content select, #content input, #content textarea'),
    dataType: 'json',
    beforeSend: function() {
      $('#button-send').button('loading');
    },
    complete: function() {
      $('#button-send').button('reset');
    },
    success: function(json) {
      $('.alert, .text-danger').remove();

      if (json['error']) {
        if (json['error']['warning']) {
          $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
        }

        if (json['error']['subject']) {
          $('input[name=\'subject\']').after('<div class="text-danger">' + json['error']['subject'] + '</div>');
        }

        if (json['error']['message']) {
          $('textarea[name=\'body\']').parent().append('<div class="text-danger">' + json['error']['message'] + '</div>');
        }
      }

      if (json['success']) {
        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
      }
    }
  });
});



var topic_value_row = <?php echo $topic_value_row; ?>;

function addTopicValue() {
  html  = '<tr id="topic-value-row' + topic_value_row + '">'; 
  html += '  <td class="text-left"><input type="hidden" name="topic_value['+ topic_value_row +'][id]" value="new_id"><input type="text" class="form-control" name="topic_value[' + topic_value_row + '][label]" value="" id="input-label' + topic_value_row + '" /></td>';
  html += '<td class="text-right"><input type="text" name="topic_value[' + topic_value_row + '][topic]" value="" placeholder="" class="form-control" /></td>';
  html += '  <td class="text-left"><button type="button" onclick="$(\'#topic-value-row' + topic_value_row + '\').remove();" data-toggle="tooltip" title="Remove" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
  html += '</tr>';  
  
  $('#topic-value tbody').append(html);
  
  topic_value_row++;
}


//--></script>


<?php echo $footer; ?>