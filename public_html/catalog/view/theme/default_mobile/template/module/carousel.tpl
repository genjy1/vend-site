 <div class="partners">
  <?php foreach ($banners as $banner) { ?>
  <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>"><div class="item"><img class="lazy" data-src="<?php echo $banner['image']; ?>"" alt="<?php echo $banner['title']; ?>"></div></a>
  <? } else { ?>
    <div class="item"><img class="lazy" data-src="<?php echo $banner['image']; ?>"" alt="<?php echo $banner['title']; ?>"></div>
  <? } ?>
  <? } ?>
  </div>
  <script>
$(document).ready(function() {
  $("#feedback").feedback();

  $(".partners").owlCarousel({
 
      autoPlay: 3000, //Set AutoPlay to 3 seconds
      lazyLoad:true,
      items : 5,
      navigation : true,
      pagination : false,
      navigationText : ["", ""],
      itemsDesktop : [1199,3],
      itemsDesktopSmall : [979,3]
 
  });
 
});
  </script>