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
      <div class="flex-container">
          <aside class="policies">
              <ul class="policies__list">
                  <li class="policies__item"><a class="policies__link" href="/agreement">Согласие на обработку данных</a></li>
                  <li class="policies__item"><a class="policies__link" href="/privacy">Политика конфиденциальности</a></li>
                  <li class="policies__item"><a class="policies__link" href="/metrika">Согласие на обработку персональных данных с помощью сервиса «Яндекс Метрика»

                      </a></li>
                  <li class="policies__item"><a class="policies__link" href="/cookies">Политика обработки файлов cookies</a></li>
              </ul>
          </aside>

          <div class="textcontent">
              <h1><? echo $heading_title; ?></h1>
              <? echo $description ?>
          </div>
      </div>
    </div>
<?php echo $footer; ?>