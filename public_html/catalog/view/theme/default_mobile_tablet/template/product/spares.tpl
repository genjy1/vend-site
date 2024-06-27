<?php echo $header; ?>
    <div class="lc">
    <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
      <?php } ?>
    </ul>
<?php echo $content_top; ?>
    </div>
      <div class="spares">
      <div class="lc">
        <h1>Запчасти</h1>
      </div>
        <? $c = 1; ?>
        <? $categories = array_reverse($categories); ?>
        <? foreach($categories as $category){ ?>
          <div class="column">
          <div class="lc">
            <div class="title torg_avt"><h2><? echo $category['name'] ?></h2></div>
          </div>
            <img src="image/spares<? echo $c ?>.jpg">
            <div class="lc">
                <div class="avtomaty_list list_avt">
                <? foreach($category['children'] as $child) {?>
                  <a href="<? echo $child['href'] ?>"><? echo $child['name'] ?></a>
                <? } ?>
                </div>
            </div>
        </div>
        <? $c++; ?>
        <? } ?>

      </div>

<?php echo $footer; ?>