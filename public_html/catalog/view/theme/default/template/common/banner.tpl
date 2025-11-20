<div class="swiper swiper1" data-swiper-autoplay="3000">
    <div class="swiper-wrapper">

        <?php
    // Карта соответствия цвета → CSS-класс
    $colorClassMap = [
        '#ffffff' => 'btn-white',
        '#ff0000' => 'btn-red',
        '#ff8000' => 'btn-orange',
        ];
        ?>

        <? foreach ($slides as $key => $value) {
        $color = strtolower($value['color_button']);
        $class = $colorClassMap[$color] ?? 'btn-default';
        ?>

        <div class="swiper-slide">
            <div class="banner-coffee" style="background: url('/image/<? echo $value['bg']?>') repeat 50% 0; background-size: cover">
                <div class="lc">

                    <? if ($value['image']) { ?>
                    <img src="/image/<? echo $value['image']?>" alt="" />
                    <? } ?>

                    <? if ($value['description']['caption'] == "" && $value['description']['text'] == "") { ?>

                    <a href="<? echo $value['links'][0] ?>"
                       style="background-color: <?= $color ?>; position:absolute; bottom: 20px; left: 20px;">
                        <?= $value['description']['text_link'] ?>
                    </a>

                    <? } else { ?>

                    <div class="text">
                        <div class="title" style="color: <? echo $value['color_caption']?>">
                            <? echo $value['description']['caption']?>
                        </div>

                        <div class="desc" style="color: <? echo $value['color_text']?>">
                            <? echo $value['description']['text']?>
                        </div>

                        <? if ($value['links'][0] == "callme") { ?>

                        <button
                                type="button"
                                class="callme banner-coffee-link <?= $class ?>"
                                data-target="#winMain"
                        >
                            <?= $value['description']['text_link'] ?>
                        </button>

                        <? } else { ?>

                        <a href="<? echo $value['links'][0] ?>"
                           style="background-color: <?= $color ?>">
                            <?= $value['description']['text_link'] ?>
                        </a>

                        <? } ?>
                    </div>

                    <? } ?>

                </div>
            </div>
        </div>

        <? } ?>

    </div>
    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</div>

<script>
    $(document).ready(function(){
        var swiper = new Swiper(".swiper1", {
            loop: true,
            autoplay: true,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            }
        });
    });
</script>
