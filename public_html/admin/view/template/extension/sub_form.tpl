<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-buket-group" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-buket-group" class="form-horizontal">


          <div class="form-group required">
            <label class="col-sm-2 control-label">Поддомен</label>
            <div class="col-sm-10">

              <div class="input-group">
                <input type="text" name="sub" value="<? if(isset($sub['sub'])) {echo $sub['sub'];} ?>" placeholder="" class="form-control" />
              </div>

            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label">Страна</label>
            <div class="col-sm-10">

              <div class="input-group">
                <input type="text" name="country" value="<? if(isset($sub['country'])) {echo $sub['country'];} ?>" placeholder="" class="form-control" />
              </div>

            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label">Страна в скобках</label>
            <div class="col-sm-10">

              <div class="input-group">
                <input type="text" name="country2" value="<? if(isset($sub['country2'])) {echo $sub['country2'];} ?>" placeholder="" class="form-control" />
              </div>

            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label">Город</label>
            <div class="col-sm-10">

              <div class="input-group">
                <input type="text" name="city" value="<? if(isset($sub['city'])) {echo $sub['city'];} ?>" placeholder="" class="form-control" />
              </div>

            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label">Город Край Округ 2</label>
            <div class="col-sm-10">

              <div class="input-group">
                <input type="text" name="city2" value="<? if(isset($sub['city2'])) {echo $sub['city2'];} ?>" placeholder="" class="form-control" />
              </div>

            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label">В род падеже</label>
            <div class="col-sm-10">

              <div class="input-group">
                <input type="text" name="pad" value="<? if(isset($sub['pad'])) {echo $sub['pad'];} ?>" placeholder="" class="form-control" />
              </div>

            </div>
          </div>

          <div class="form-group required">
            <label class="col-sm-2 control-label">Описание для главной страницы</label>
            <div class="col-sm-10">

              <div class="input-group">
                <textarea name="mainpage" placeholder="" id="mainpage" class="form-control"><? if(isset($sub['mainpage'])) {echo $sub['mainpage'];} ?></textarea>
              </div>

            </div>
          </div>


        </form>
      </div>
    </div>
  </div>
</div>
<script>
$('#mainpage').summernote({
  height: 300
});
</script>
<?php echo $footer; ?>