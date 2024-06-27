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
      <div class="er404">
        <h1>Ошибка 404</h1>
        <div>Страница не найдена</div>
        <div class="links"><a href="/">Перейти на главную страницу</a><a href="/category/avtomaty/">Каталог торговых автоматов</a></div>
      </div>
    </div>
<?php echo $footer; ?>