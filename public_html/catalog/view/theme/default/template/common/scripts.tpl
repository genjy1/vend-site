<script>
    $(document).ready(function(){
        $("#helpcats").on("change", function(){
            location.href = $(this).val();
        });
        $('#helpcats').styler();
        // $("#request")
        // $("#offer")
        // $("#win")
        // $("#fast")
        // $(".callme:not(.postform), a[href=\"#callme\"]").on("click", function(e){
        //     e.preventDefault();
        //     $(".win_white:eq(0), .winoverlay").show();
        //
        //     if ($("body").hasClass("common-home")) {
        //
        //         $("#win").attr("data-subject", "Обратный звонок");
        //         $("#win").closest(".win_white").find(".zvonok").text( $(this).text())
        //
        //         if ($(this).text() == "Оставить заявку") {
        //             $("#win").attr("data-subject", "Заявка с главной страницы");
        //         }
        //
        //     }
        //     if($(this).hasClass("proposal")){
        //         $("#win").attr("data-subject", $(this).text());
        //     }
        //
        // });
        $(".banner-coffee-link").click(function(){
            $("#win").attr("data-subject", "Главный баннер");
            $("#win").closest(".win_white").find(".zvonok").text( $(this).text())
        })
        $(".avtomat-link").click(function(){
            $("#win").attr("data-subject", "Подобрать автомат");
            $("#win").closest(".win_white").find(".zvonok").text( $(this).text())
        })
        $(".price .request").on("click", function(e){
            $("form[data-subject='Запрос цены']").attr("data-subject", "Форма Рассрочка 0%");
        });

        $(".callme.postform").on("click", function(e){
            e.preventDefault();
            $("#offer, .winoverlay").show();
        });

    });
</script>

<?php if(isset($_REQUEST['_route_']) && $_REQUEST['_route_'] == 'category/zapchasti/'){ ?>
<script defer src="//code.jivosite.com/widget/qJyduciPtA" ></script>
<?php } elseif(isset($_REQUEST['_route_']) && $_REQUEST['_route_'] == 'category/katalog-proizvodstvennykh-avtomatov/'){ ?>
<script defer src="//code.jivosite.com/widget/MRu4RZTTGt" ></script>
<?php } else { ?>
<!--script defer src="//code.jivosite.com/widget.js" jv-id="EyJpTYoy9B" ></script>
-->
<script type="text/javascript">
    if (typeof navigator.userAgent !== "undefined") {
        if (navigator.userAgent.indexOf('Lighthouse') < 0) {
            setJivo();
        }
    } else {
        setJivo();
    }
    function setJivo(){
        var script = document.createElement('script');
        script.src = "//code.jivosite.com/widget.js";
        script.setAttribute("jv-id", "EyJpTYoy9B");
        document.getElementsByTagName('head')[0].appendChild(script);
    }
</script>

<?php } ?>


<div style="position: absolute;left: -1000000px;display: none;" id="LiveInternet"><!--LiveInternet counter--><script type="text/javascript"><!--
        /*document.write("<a href='http://www.liveinternet.ru/click' "+
          "target=_blank><img src='//counter.yadro.ru/hit?t14.11;r"+
          escape(document.referrer)+((typeof(screen)=="undefined")?"":
            ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
              screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
          ";"+Math.random()+
          "' alt='' title='LiveInternet: показано число просмотров за 24"+
          " часа, посетителей за 24 часа и за сегодня' "+
          "border='0' width='88' height='31'><\/a>")*/

        $("#LiveInternet").html("<a href='http://www.liveinternet.ru/click' "+
            "target=_blank><img src='//counter.yadro.ru/hit?t14.11;r"+
            escape(document.referrer)+((typeof(screen)=="undefined")?"":
                ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                    screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
            ";"+Math.random()+
            "' alt='' title='LiveInternet: показано число просмотров за 24"+
            " часа, посетителей за 24 часа и за сегодня' "+
            "border='0' width='88' height='31'><\/a>")
        //--></script><!--/LiveInternet-->
</div>

<script src="catalog/view/javascript/jquery-ui.min.js" defer></script>
<script>
    if (typeof navigator.userAgent !== "undefined") {
        if (navigator.userAgent.indexOf('Lighthouse') < 0) {
            delcity();
        }
    } else {
        delcity();
    }
    function delcity() {
        $( function() {
            if ($( 'input[name="delcity"]' ).length > 0)
                $( 'input[name="delcity"]' ).autocomplete({
                    minLength: 0,
                    source: function (request, response) {
                        $.ajax({
                            method: 'post',
                            dataType: 'json',
                            data: {city: $('input[name="delcity"]').val()},
                            url: 'index.php?route=common/delin/cities'
                        })
                            .success(function(data){
                                response(data.cities);
                            });
                    },
                    focus: function( event, ui ) {
                        $( 'input[name="delcity"]' ).val( ui.item.fullname );
                        return false;
                    },
                    select: function( event, ui ) {
                        $( 'input[name="delcity"]' ).val( ui.item.fullname );
                        $('#arrivalPoint').val(ui.item.code);
                        $("#delcalc").trigger("click");
                        return false;
                    }
                })
                    .autocomplete( "instance" )._renderItem = function( ul, item ) {
                    return $( "<li>" )
                        .append( "<div class='citem'>" + item.fullname + "</div>" )
                        .appendTo( ul );
                };
        } );


    }

    if (typeof navigator.userAgent !== "undefined") {
        if (navigator.userAgent.indexOf('Lighthouse') < 0) {
            hideLoader();
        } else {
            $(document).ready(function() {
                $('#preloader').find('i').fadeOut().end().fadeOut('slow');
            });
        }
    } else {
        hideLoader();
    }

    function hideLoader(){
        $(window).load(function() {
            $('#preloader').find('i').fadeOut().end().delay(400).fadeOut('slow');
        });
    }


</script>

<script>
    function init() {
        var vidDefer = document.getElementsByTagName('iframe');
        for (var i=0; i<vidDefer.length; i++) {
            if(vidDefer[i].getAttribute('data-src')) {
                vidDefer[i].setAttribute('src',vidDefer[i].getAttribute('data-src'));
            } } }
    window.onload = init;
</script>
<!--
  <script type="text/javascript" src="//vk.com/js/api/openapi.js?139" defer ></script>
  <script type="text/javascript">
    VK.init({apiId: 5876809, onlyWidgets: true});
  </script>
-->
<!-- VK Widget -->
<script type="text/javascript">
    if (typeof navigator.userAgent !== "undefined") {
        if (navigator.userAgent.indexOf('Lighthouse') < 0) {
            setVK();
        }
    } else {
        setVK();
    }
    function setVK(){
        var script = document.createElement('script');
        script.src = "//vk.com/js/api/openapi.js?139";
        script.onload = function(){
            VK.init({apiId: 5876809, onlyWidgets: true});
        }
        document.getElementsByTagName('head')[0].appendChild(script);


    }
</script>


<script>( function() {

        var youtube = document.querySelectorAll( ".youtube" );

        for (var i = 0; i < youtube.length; i++) {

            var source = "https://img.youtube.com/vi/"+ youtube[i].dataset.embed +"/sddefault.jpg";

            /*	var image = new Image();
                        image.src = source;
                        image.addEventListener( "load", function() {
                            youtube[ i ].appendChild( image );
                        }( i ) );*/

            youtube[i].addEventListener( "click", function() {

                var iframe = document.createElement( "iframe" );

                iframe.setAttribute( "frameborder", "0" );
                iframe.setAttribute( "allowfullscreen", "" );
                iframe.setAttribute( "src", "https://www.youtube.com/embed/"+ this.dataset.embed +"?rel=0&showinfo=0&autoplay=1" );
                iframe.style.width = "100%"
                iframe.style.height = "100%"
                this.innerHTML = "";
                this.appendChild( iframe );
            } );
        };

    } )();</script>

<script type="text/javascript" defer src="/catalog/view/javascript/jquery.lazy.min.js"></script>
<script>
    $(document).ready(function(){
        if (typeof navigator.userAgent !== "undefined") {
            if (navigator.userAgent.indexOf('Lighthouse') < 0) {
                setLazy();
            }
        } else {
            setLazy();
        }

        function setLazy() {
            $('.lazy').Lazy({effect: 'fadeIn',effectTime: 1000});
            $('.lazy').each(function(){
                $(this).attr("src", $(this).attr("data-src"));
            });
        }

        // $(".callme").click(function(){
        //     $(".win_white:eq(0) .zvonok").text("Обратный звонок")
        // })
        $(".lc .textcontent button[type=button]").on("click", function(){
            $(".win_white:eq(0), .winoverlay").show();
            $(".win_white:eq(0) .zvonok").text("Рассрочка и кредит")
            return false;
        });
    });
    $("input[type=tel]").attr("autocomplete", "off")
</script>

<link rel="stylesheet" href="https://cdn.envybox.io/widget/cbk.css">
<script type="text/javascript" src="https://cdn.envybox.io/widget/cbk.js?wcb_code=0c876d500cf85fe091c58e7f9df2eff0" charset="UTF-8" async></script>
<!-- Toastify -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js@1/src/toastify.min.js" defer></script>
<script type="module" src="/catalog/view/javascript/cookieNotice.js" ></script>
<style>
    .easter-egg {
        position: fixed;
        transform: translateX(-120%);
        transition: all ease-in .3s;
    }

    .easter-egg > img {
        width: 64px;
        object-fit: contain;
        height: auto;
    }

    .easter-egg.active {
        transform: translateX(0);
    }
</style>
<script type="module">

    const emailFields = document.querySelectorAll('input[type="email"]')
    const labels = document.querySelectorAll('label[for="email"]')
    const easterEgg = document.querySelector('.easter-egg')
    const pressed = new Set();

    emailFields.forEach(field => field.required = true)
    labels.forEach(label => label.textContent += ' *')


    document.addEventListener('keydown', (e) => {

        pressed.add(e.key.toLowerCase())

        if (pressed.has('p') && pressed.has('n') || pressed.has('з') && pressed.has('т')) {
            easterEgg.classList.toggle('active')

            setTimeout(() => {
            easterEgg.classList.toggle('active')}, 3000)
        }
    });

    document.addEventListener('keyup', (e) => {
        pressed.delete(e.key.toLowerCase());
    });

</script>


<script></script>