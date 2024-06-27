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
      <div class="news">
        <h1><? echo $heading_title ?></h1>
        <div class="nc">
          <? $i = 0; ?>
          <? if(empty($posts)) { ?>  <? } ?>
          <? foreach($posts as $post){ ?>
            <? if($i == 0){?> <div class="row"> <?}?>
            <div class="nblock"><a href="<? echo $post['href'] ?>"><img class="lazy" data-src="<? echo $post['image'] ?>"><span <? if(isset($cats)){ ?>class="posttile"<? } ?>>
              <? echo $post['title']; ?>
            </span></a>
              <div class="date"><? echo $post['date']; ?></div>
              <div class="intro"><? echo $post['description']; ?></div>

        <? if(isset($post['time_end']) && $post['time_end']) {?>
          <div class="timetext" style="margin-top: 10px;">До конца акции осталось</div>
          <div style="margin-top: 10px;">
            <div id="counter<? echo $post['id']; ?>" data-date="<? echo $post['time_end']; ?> 00:00:00"></div>
          </div>
          <script>
            $(document).ready(function(){
              $('#counter<? echo $post['id']; ?>').TimeCircles();
            });
          </script>
        <? } ?>
            </div>
            <? $i++; ?>
            <? if($i == 3){ $i = 0; ?> </div> <?}?>
          <? } ?>
          <? if($i != 0) {?> </div> <? } ?>
        </div>
        <? echo $pagination; ?>
      </div>
    </div>
<?php echo $footer; ?>