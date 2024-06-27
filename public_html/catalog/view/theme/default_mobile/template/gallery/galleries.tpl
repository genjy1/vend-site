<?php echo $header; ?>
    <div class="lc">
      <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
    <?php } ?>
      </ul>
      <h1><? echo $heading_title; ?></h1>
      </div>
      <div class="galery-content">

  <? foreach($galleries as $gallery){ ?>
    <div> 
      <a href="<? echo $gallery['href'] ?>"><img class="lazy" data-width="100%"  src="<? echo $gallery['image'] ?>"  alt="">
         <div class="lc"><div class="title"><? echo $gallery['title'] ?></div>
            <? if( $gallery['type'] == 'video' ){ ?>
               <div class="vcount"><? echo $gallery['count'] ?> видеозаписи</div>
                <div class="vid_play"></div>
            <? } ?>
          </div>
      </a>
    </div>
  <? } ?>
   </div>


<?php echo $footer; ?>