<?php echo $header; ?>
<div class="lc">
    <ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <?php $i=1; foreach ($breadcrumbs as $breadcrumb) { ?>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="<?= $breadcrumb['href']; ?>" itemprop="name"><?= $breadcrumb['text']; ?></a><span>/</span>
            <meta itemprop="position" content="<?= $i ?>" />
        </li>
        <?php $i++; } ?>
    </ul>

    <div class="flex-container">

        <aside class="policies">
            <ul class="policies__list">
                <li><a class="policies__link" href="/agreement">Согласие на обработку данных</a></li>
                <li><a class="policies__link" href="/privacy">Политика конфиденциальности</a></li>
                <li><a class="policies__link" href="/metrika">Согласие на обработку данных с помощью «Яндекс Метрика»</a></li>
                <li><a class="policies__link" href="/cookies">Политика обработки cookies</a></li>
            </ul>
        </aside>

        <div class="textcontent">
            <h1><?= htmlspecialchars($heading_title) ?></h1>
            <?= $description ?>
        </div>
    </div>
</div>
<?php echo $footer; ?>
