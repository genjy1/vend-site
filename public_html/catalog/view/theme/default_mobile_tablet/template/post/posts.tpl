<?php echo $header; ?>
    <div class="lc">
      <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
    <?php } ?>
      </ul>
      <div class="news">
        <h1><? echo $heading_title ?></h1>
        <div class="nc">
          <? $i = 0; ?>
          <? if(empty($posts)) { ?><? } ?>
          <? foreach($posts as $post){ ?>
            <div class="nblock"><a href="<? echo $post['href'] ?>"><img src="<? echo $post['image'] ?>"><span>
              <? echo $post['title']; ?>
            </span></a>
              <div class="date"><? echo $post['date']; ?></div>
              <div class="intro"><? echo $post['description']; ?></div>
            </div>
            <? $i++; ?>
          <? } ?>
          <? if($i != 0) {?> </div> <? } ?>
        </div>
        <? echo $pagination; ?>
      </div>
    </div>
<?php echo $footer; ?>