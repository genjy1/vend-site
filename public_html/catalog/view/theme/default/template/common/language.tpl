<?php if (count($languages) > 1) { ?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-language" class="lang">
    <?php foreach ($languages as $language) { ?>

      <a href="javascript:void(0)" class="hidden" data-name="<?php echo $language['code']; ?>"><img class="lazy" data-src="catalog/language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>"></a>
    <?php } ?>
  <input type="hidden" name="code" value="" />
  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
</form>
<?php } ?>
