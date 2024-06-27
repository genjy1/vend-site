  <div class="banner" id="slider">
		<div class="sliderContent">
		<? foreach($slides as $slide) { ?>
			<div>
			<div class="itembg" style="<? if($slide['background'] != ''){ echo 'background: url(' . $slide['background'] . ')'; } else {?> background: url(/image/home/bg_banner.png); <? } ?>">
			<div class="item" data-bg="<? if($slide['background'] != ''){ echo $slide['background']; } ?>">
				<? if($slide['position'] == 0){ $class="left"; } else { $class = "right"; } ?>
					<div class="textblock <? echo $class ?>">
						<div class="caption" style="color: <? echo $slide['color_caption'] ?>">
							<? echo $slide['caption'] ?>
						</div>
						<div class="text" style="color: <? echo $slide['color_text'] ?>">
							<? echo $slide['text'] ?>
						</div>
						<div class="links" <? if($slide['text'] == "") {?> style="bottom: -24vw;;position: absolute;" <? } ?> >
							<? foreach($slide['links'] as $k => $link){ ?>
								<? if(isset($slide['text_links'][$k]) && $slide['text_links'][$k] != ''){ ?>
									<a href="<? echo $link ?>" class="goto" <? if($slide['color_button'] != "#ffffff") { ?> style="background: <? echo $slide['color_button'] ?> " <? } ?>><? echo $slide['text_links'][$k] ?></a>
								<? } else { ?>
									<a href="<? echo $link ?>" class="goto" <? if($slide['color_button'] != "#ffffff") { ?> style="background: <? echo $slide['color_button'] ?> " <? } ?>>Перейти к автомату</a>
								<? } ?>
							<? } ?>
						</div>
<? if(isset($slide['time_end']) && $slide['time_end']) {?>
			<div class="slidertimer">
          <div class="timetext" style="margin-top: 10px;">До конца акции осталось</div>
          <div style="margin-top: 10px;">
            <div id="counter<? echo $slide['id']; ?>" data-date="<? echo $slide['time_end']; ?> 00:00:00"></div>
          </div>
          </div>
          <script>
            $(document).ready(function(){
              $('#counter<? echo $slide['id']; ?>').TimeCircles();
            });
          </script>
        <? } ?>
					</div>
					<? if($class == "left"){ $class="right"; } else { $class = "left"; } ?>
				<img class="simg<? echo $class ?>" src="<? echo $slide['image'] ?>" alt="" />
			</div>
			</div>
			</div>
		<? } ?>
		</div>
  </div>
  <script>
  	$(document).ready(function(){
	$('#slider').mobilyslider({
		transition: '<? echo $effect; ?>',
		animationSpeed: 500,
		autoplay: true,
		autoplaySpeed: 8000,
		pauseOnHover: true,
		bullets: true,
		arrowsHide: false,
		animationStart: function(){},
		animationComplete: function(){
			// if($(".sliderContent .item:visible").data("bg") != ''){
			// 	bg = $(".sliderContent .item:visible").data("bg");
			// 	$( '.banwrap').each(function () {
			// 		this.style.setProperty( 'background', 'url('+bg+')', 'important' );
			// 	});
			// } else {
			// 	$( '.banwrap').removeAttr('style');
			// }
			// ind = $(".sliderContent .item:visible").index();
			// $( '.banwrap').addClass("sl" + ind);
		}
	});

	$('#slider .item').on("click", function(){
		href = $(this).find('a').eq(0).attr('href');
		if(href == "#"){
			$(".callme").trigger("click");
		} else {
			// location.href = href;
		}
	});

  	});
  </script>