<?php echo $header; ?>
    <div class="lc">
    <ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
    <?php $i=1; foreach ($breadcrumbs as $breadcrumb) { ?>
      <li itemprop="itemListElement" itemscope
      itemtype="https://schema.org/ListItem">
      <a href="<?php echo $breadcrumb['href']; ?>" itemprop="name"><?php echo $breadcrumb['text']; ?></a><span>/</span>
      <meta itemprop="position" content="<?=$i?>" />
      </li>
    <?php $i++; } ?>
  </ul>
      <div class="textcontent">
        <h1><? echo $heading_title; ?></h1>
        <? echo $description ?>
      </div>
    </div>
<?php echo $footer; ?>