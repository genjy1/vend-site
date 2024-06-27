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
<?php echo $content_top; ?>

      <div class="spares">
        <h1>Запчасти</h1>
        <? $c = 1; ?>
        <? $categories = array_reverse($categories); ?>
        <? foreach($categories as $category){ ?>
        <div class="column">
          <div class="title"><? echo $category['name'] ?></div><img class="lazy" data-src="image/spares<? echo $c ?>.jpg">
                <div id="pa">
                <? foreach($category['children'] as $child) {?>
                  <a href="<? echo $child['href'] ?>"><? echo $child['name'] ?></a>
                <? } ?>
                </div>
        </div>
        <? $c++; ?>
        <? } ?>

      </div>
    </div>

<?php echo $footer; ?>