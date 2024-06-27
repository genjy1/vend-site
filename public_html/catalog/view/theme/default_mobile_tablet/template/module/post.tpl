<? if($posts){ ?>
<div class="container">
    <div class="col-sm-6 col-sm-12">
        <div class="blockanswers">
            <h2><? echo $title ?></h2>
            <?php foreach ($posts as $post) { ?>
            <div class="letter">
                <img src="<? echo $post['thumb'] ?>" alt="">
                <p><a href="<? echo $post['href'] ?>" class="article">Как оформить заказ на доставку букета?</a>
                Редукционизм как философский подход исторически
потеснил холизм — систему взглядов, не выделяемую
в тот период отдельно, но господствовавшую в
европейском мышлении до XVII века. Первым
последовательным выразителем... <a href="<? echo $post['href'] ?>" class="more">подробнее</a>
                </p>
            </div>
            <? } ?>
            <a href="" class="allletters">
                Посмотреть все
            </a>
        </div>
    </div>

</div>

<? } ?>