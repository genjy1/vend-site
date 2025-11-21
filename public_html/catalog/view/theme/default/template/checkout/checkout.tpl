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
      <div class="cart">
        <h1>Контактная информация</h1>
        <div class="checkout">
          <form method="post" id="checkout">
            <div class="form-group">
              <label for="firstname">Имя<span class="req">*</span></label>
              <input class="input input-text" name="firstname" id="firstname"  type="text" required>
            </div>
            <div class="form-group"  type="text">
              <label for="telephone">Телефон<span class="req">*</span></label>
              <input class="input input-text" name="telephone" id="telephone"  type="text" required>
            </div>
            <div class="form-group">
              <label for="email">Email</label>
              <input class="input input-text" name="email" id="email"  type="text">
            </div>
            <button type="submit" class="btn btn-submit-checkout">Дальше</button>
          </form>
        </div>
      </div>
    </div>
<script type="module" src="/catalog/view/javascript/feedback/checkout.js" ></script>
<?php echo $footer; ?>