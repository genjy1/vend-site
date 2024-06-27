<?php echo $header; ?>
    <div class="lc">
      <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
    <?php } ?>
      </ul>
      <div class="textcontent">
        <h1><? echo $heading_title; ?></h1>
        <? echo $description ?>
      </div>
    </div>
<?php echo $footer; ?>