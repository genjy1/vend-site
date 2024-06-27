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
                <label class="col-sm-2 control-label" for="input-usename"><? echo $text_usename ?></label>
                <div class="col-sm-10">
                  <input type="checkbox" name="usename" id="input-usename" class="form-control" <? if($usename > 0){ ?> checked <? } ?>>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-width"><? echo $text_width ?></label>
                <div class="col-sm-10">
                  <input type="text" name="width" value="<? echo $width ?>" id="input-width" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input-height"><? echo $text_height ?></label>
                <div class="col-sm-10">
                  <input type="text" name="height" value="<? echo $height ?>" id="input-height" class="form-control">
                </div>
            </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-tax-class"><?php echo $text_position; ?></label>
                <div class="col-sm-10">
                  <select name="position" id="input-tax-class" class="form-control">
                    <option value="1" <? if($position==1){ ?>selected<? } ?>><? echo $text_top_left ?></option>
                    <option value="2" <? if($position==2){ ?>selected<? } ?>><? echo $text_top_center ?></option>
                    <option value="3" <? if($position==3){ ?>selected<? } ?>><? echo $text_top_right ?></option>
                    <option value="4" <? if($position==4){ ?>selected<? } ?>><? echo $text_center_left ?></option>
                    <option value="5" <? if($position==5){ ?>selected<? } ?>><? echo $text_center_center ?></option>
                    <option value="6" <? if($position==6){ ?>selected<? } ?>><? echo $text_center_right ?></option>
                    <option value="7" <? if($position==7){ ?>selected<? } ?>><? echo $text_bottom_left ?></option>
                    <option value="8" <? if($position==8){ ?>selected<? } ?>><? echo $text_bottom_center ?></option>
                    <option value="9" <? if($position==9){ ?>selected<? } ?>><? echo $text_bottom_right ?></option>
                  </select>
                </div>
              </div>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php echo $footer; ?>
