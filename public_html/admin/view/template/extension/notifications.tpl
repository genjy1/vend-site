<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <!-- <button type="submit" form="form-post" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button> -->
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $label['heading_title']; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Уведомления</h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-post" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active">
              <a href="#tab-push" data-toggle="tab"><?php echo $label["tab_push"]; ?></a>
            </li>
            <li>
              <a href="#tab-email" data-toggle="tab"><?php echo $label["tab_email"]; ?></a>
            </li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-push">

              <div class="form-group">
                <label class="col-sm-12 control-label" for="input-name">Всего в базе <?php echo $push_subscribers; ?> подписчиков</label>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name">Title</label>
                <div class="col-sm-10">
                  <input type="text" name="title" value="<?php echo isset($name) ? $name : ''; ?>" placeholder="<?php echo $label['entry_name']; ?>" id="input-name" class="form-control" required="required" />
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name">Body</label>
                <div class="col-sm-10">
                  <textarea name="body" placeholder="<?php echo $label['entry_body']; ?>" id="input-body" class="form-control" required="required" ><?php echo isset($body) ? $body : ''; ?></textarea>
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-url">Url</label>
                <div class="col-sm-10">
                  <input type="text" name="url" value="<?php echo isset($url) ? $url : ''; ?>" placeholder="<?php echo $label['entry_url']; ?>" id="input-url" class="form-control" required="required" />
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-image">Icon</label>
                <div class="col-sm-10">
                  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />


                  <input type="hidden" name="topic" value="default">

                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                  <button type="button" class="btn btn-primary" id="sendpush">Отправить</button>
                </div>
              </div>
            </div>

            <div class="tab-pane" id="tab-email">
              <div class="form-group">
                <label class="col-sm-12 control-label" for="input-name">Всего в базе <?php echo $email_subscribers; ?> подписчиков</label>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name">Тема</label>
                <div class="col-sm-10">
                  <input type="text" name="subject" value="<?php echo isset($name) ? $name : ''; ?>" placeholder="<?php echo $label['entry_name']; ?>" id="input-name" class="form-control" required="required" />
                </div>
              </div>

              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-message">Сообщение</label>
                <div class="col-sm-10">
                  <textarea name="message" placeholder="<?php echo $label['entry_body']; ?>" id="input-message" class="form-control ckeditor" required="required" ><?php echo isset($message) ? $message : ''; ?></textarea>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                  <button type="button" class="btn btn-primary" id="sendmail">Отправить</button>
                </div>
              </div>

            </div>

          </div>
        </form>



        <div class="modal fade" id="modal-progress">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"> <i class="fa fa-fw fa-bell"></i> Отправка уведомления</h4>
              </div>
              <div class="modal-body">
                <div class="progress">
                  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%;">
                  <span class="sr-only">100% Complete</span>
                  </div>
                </div>
                <div class="alert alert-success hide" role="alert">
                  Уведомления отправлены подписчикам
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
          </div>
        </div>
      </div>

      </div>
    </div>
  </div>

  
</div>
<script>
// $('input[name=\'url\']').autocomplete({
//   'source': function(request, response) {
//     $.ajax({
//       url: 'index.php?route=extension/notifications/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
//       dataType: 'json',
//       success: function(json) {
//         response($.map(json, function(item) {
//           return {
//             label: item['name'],
//             value: item['link']
//           }
//         }));
//       }
//     });
//   },
//   'select': function(item) {
//     alert();
//     $('input[name=\'url\']').val(item['link']);
//   }
// });
</script>

<script type="text/javascript"><!--
maileditor = CKEDITOR.replace('input-message', {
language: 'en',
skin : 'kama',
height : '300px'
});
maileditor.on('change', function(event) {
  $('textarea[name=\'message\']').html(event.editor.getData());
});
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


$("#sendpush").on("click", function(){

    $("#modal-progress .alert").addClass("hide");
    $("#modal-progress .progress").show();

    error = false;

    $(".form-group").removeClass("has-error");

    $('#tab-push input, #tab-push textarea').map(function(){
      if($(this).val() == ''){
        error = true;
        $(this).closest(".form-group").addClass("has-error");
      }
    });

    if(error){
      return false;
    }

    $("#modal-progress").modal('show');

    $.ajax({
    url: "index.php?route=extension/notifications/sendPush&token=<?php echo $token; ?>",
    type: 'post',
    data: $('#tab-push select, #tab-push input, #tab-push textarea'),
    dataType: 'json',
    beforeSend: function() {
      $('#button-send').button('loading');
    },
    complete: function() {
      $('#button-send').button('reset');
    },
    success: function(json) {
      if (json['error']) {
        if (json['error']['warning']) {
          $("#modal-progress .alert").text(json['error']['warning']);
          $("#modal-progress .progress").hide();
          $("#modal-progress .alert").removeClass("hide");
        }
      }

      if (json['success']) {
        $("#modal-progress .alert").text(json['success']);
        $("#modal-progress .progress").hide();
        $("#modal-progress .alert").removeClass("hide");
      }
    }
  });
});

$("#sendmail").on("click", function(){

    $("#modal-progress .alert").addClass("hide");
    $("#modal-progress .progress").show();

    error = false;

    $(".form-group").removeClass("has-error");

    $('#tab-email input, #tab-email textarea').map(function(){
      if($(this).val() == ''){
        error = true;
        $(this).closest(".form-group").addClass("has-error");
      }
    });

    if(error){
      return false;
    }

    $("#modal-progress").modal('show');


    $.ajax({
    url: "index.php?route=extension/notifications/sendMail&token=<?php echo $token; ?>",
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
      if (json['error']) {
        if (json['error']['warning']) {
          $("#modal-progress .alert").text(json['error']['warning']);
          $("#modal-progress .progress").hide();
          $("#modal-progress .alert").removeClass("hide");
        }
      }

      if (json['success']) {
        $("#modal-progress .alert").text(json['success']);
        $("#modal-progress .progress").hide();
        $("#modal-progress .alert").removeClass("hide");
      }
    }
  });
});

//--></script>


<?php echo $footer; ?>