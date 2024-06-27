<?php echo $header; ?>
    <div class="lc">
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><span>/</span></li>
        <?php } ?>
      </ul>
      <div class="er404">
        <h1>Ошибка 404</h1>
        <div>Страница не найдена</div>
        <div class="links"><a>Перейти на главную страницу</a><a>Каталог торговых автоматов</a></div>
      </div>
    </div>
<?php echo $footer; ?>