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
 <h1><? echo $heading_title; ?></h1>
<style>
/*  .Firefox .item:nth-child(2){
    padding: 11px!important;
  }
  .Firefox .item:nth-child(5){
    height: 431px!important;
  }*/
</style>
<div id="gallery">
  <? foreach($galleries as $gallery){ ?>
    <div class="item" style="width: <? echo $gallery['width'] ?>px"   <? echo $gallery['sort'] ?>> 
      <a href="<? echo $gallery['href'] ?>"><img width="100%"  class="lazy" data-src="<? echo $gallery['image'] ?>"  alt=""><h4><? echo $gallery['title'] ?></h4></a>
         
            <? if( $gallery['type'] == 'video' ){ ?>
                 <div class="hasvideo">
                  <div><? echo $gallery['count'] ?> видеозаписи</div>
                  <!-- <div class="play"></div> -->
                </div>
            <? } ?>
    </div>
  <? } ?>
</div>
  <script type="text/javascript"> 
      jQuery(document).ready(function(){ 
        // jQuery("#gallery").unitegallery({
        //   tiles_type:"justified"
        // }); 
        $('#gallery').imagesLoaded( function() {
          $('#gallery').isotope({
            // options
            itemSelector: '.item',
            layoutMode: 'packery'
          });
        });
        // $(".item:nth-child(1n + 6) a").css("padding-top", 2);
      }); 
    </script>
    </div>
<?php echo $footer; ?>