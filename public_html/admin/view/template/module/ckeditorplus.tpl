<?php 
// CKEditor 2+ (4.5.5)
// author: DataIc - www.dataic.eu
 ?>
<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">

<div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-ckeditorplus" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
<!-- form -->      
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-ckeditorplus" class="form-horizontal">
          <ul class="nav nav-tabs">
                      <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                      
                      <li><a href="#tab-info" data-toggle="tab"><?php echo $tab_info; ?></a></li> 
          </ul>
    <div class="tab-content">  
  <div class="tab-pane active" id="tab-general">
             <div class="form-group"> 
                <label class="col-sm-2 control-label" for="input-ckeditorplus_language"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $entry_language_info; ?>"><?php echo $entry_language; ?></span></label>
                <div class="col-sm-10">
                  <select name="ckeditorplus_language" id="input-ckeditorplus_language" class="form-control">
                    <option value="en">EN</option>
                     <?php foreach ($languages as $language) {?>
                      <option value="<?php echo $language;?>" <?php if ($language==$ckeditorplus_language){ echo " selected";}?>><?php echo strtoupper($language);?></option>
                      <?php } ?>
                  </select>  
                </div>
              </div>



            <div class="form-group"> 
                <label class="col-sm-2 control-label" for="input-ckeditorplus_skin"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $entry_skin_info; ?>"><?php echo $entry_skin; ?></span></label>
                <div class="col-sm-10">
                  <select name="ckeditorplus_skin" id="input-ckeditorplus_skin" class="form-control">
                    <option value="kama">KAMA</option>
                     <?php foreach ($skin as $skin) {?>
                      <option value="<?php echo $skin;?>" <?php if ($skin==$ckeditorplus_skin){ echo " selected";}?>><?php echo strtoupper($skin);?></option>
                      <?php } ?>
                  </select>  
                </div>
              </div>  

              <div class="form-group">   
                <label class="col-sm-2 control-label" for="input-ckeditorplus_height"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $entry_height_info; ?>"><?php echo $entry_height; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" id="input-ckeditorplus_height" name="ckeditorplus_height"  value="<?php echo $ckeditorplus_height; ?>" placeholder="<?php echo $entry_height; ?>"   class="form-control" />
                </div>
              </div>

              <div class="form-group"> 
                <label class="col-sm-2 control-label" for="input-ckeditorplus_enhanced"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $entry_enhanced_info; ?>"><?php echo $entry_enhanced; ?></span></label>
                <div class="col-sm-10">
                  <select name="ckeditorplus_enhanced" id="input-ckeditorplus_enhanced" class="form-control">
                     <?php if ($ckeditorplus_enhanced) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select> 
                </div>
              </div>
            
            <div class="form-group"> 
                <label class="col-sm-2 control-label" for="ckeditorplus_status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="ckeditorplus_status" id="input-ckeditorplus_status" class="form-control">
                     <?php if ($ckeditorplus_status) { ?>
                      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                      <option value="0"><?php echo $text_disabled; ?></option>
                    <?php } else { ?>
                      <option value="1"><?php echo $text_enabled; ?></option>
                      <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <?php } ?>
                  </select> 
                </div>
              </div>

       </div>
       
        <div class="tab-pane" id="tab-info">
          <div class="form-group"> 



            
<div class="col-sm-12"> 
<p style="font-size: 15px;"><b>Thank you for using our extensions.</b></p>
<p>To get support email us to <a href="mailto:support@dotbox.sk">support@dotbox.sk</a>.</p>
<p>We are happy to help.</p>

<p>If you like what you see leave us a comment and rate our extensions.</p>
 
<p style="font-size: 14px;">To check-out our other extensions <a href="http://www.opencart.com/index.php?route=extension/extension&filter_username=dotbox" target="_blank"><button style="padding: 3px 13px;" title="click here" class="btn btn-primary btn-success">click here</button></a> .</p>

<p style="font-size: 14px;">To get the powerful <b>ImageManagerPlus+ </b> extension <a href="http://www.opencart.com/index.php?route=extension/extension/info&extension_id=22502" target="_blank"><button style="padding: 3px 13px;" title="click here" class="btn btn-primary">click here</button></a> .</p>
</div>

          </div>
        </div> 
      </div>
     </form>   
      </div>
    </div>
  </div>
</div>

<?php echo $footer; ?>