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
<div id="gallery-set">
          <? foreach($images as $image){ ?>
              <img class="lazy" data-src="<? echo $image['thumb'] ?>" alt="<? echo $image['title'] ?>" data-image="<? echo $image['image'] ?>" data-description="<? echo $image['title'] ?>" <? if($image['videoid']!=''){ ?>data-type="youtube" data-videoid="<? echo $image['videoid'] ?>" <? } ?> style="display:none">
          <? } ?>
    </div>
<?php echo $pagination ?>

  <script type="text/javascript"> 
      
      jQuery(document).ready(function(){ 
        jQuery("#gallery-set").unitegallery({
          gallery_theme: "tilesgrid",
          theme_enable_preloader: true,
          tile_width: 208,            //tile width
          tile_height: 180,           //tile height
          tiles_justified_space_between: 3,
          tile_enable_border:false,     //enable border of the tile
          tile_enable_shadow:false,
          grid_space_between_cols: 25,      //space between columns
          grid_space_between_rows: 38,      //space between rows
          lightbox_textpanel_enable_title: true,
          tile_enable_textpanel: true,
          lightbox_textpanel_enable_description: false,
          lightbox_show_textpanel: true,
        }); 
      }); 
    
    </script>

<script type="text/javascript">




    $(document).ready(function(){

// var $container = $('.mosaicflow');

// $container.imagesLoaded( function(){
//   $container.masonry({
//     itemSelector : '.mosaicflow__item'
//   });

// });

});

    </script>


    <script type="text/javascript">
//     $(document).ready(function(){
//       var wall = new Freewall(".mosaicflow");
//       wall.reset({
//         selector: '.mosaicflow__item',
//         animate: true,
//         cellW: 150,
//         cellH: 'auto',
//         onResize: function() {
//           wall.fitWidth();
//         }
//       });

//       var images = wall.container.find('.mosaicflow__item');
//       images.find('img').load(function() {
//         wall.fitWidth();
//       });
// });
    </script>
<script>
// $('.mosaicflow').mosaicflow({
//   itemSelector: '.mosaicflow__item',
//   minItemWidth: 490,
// });
</script>

    </div>
<?php echo $footer; ?>