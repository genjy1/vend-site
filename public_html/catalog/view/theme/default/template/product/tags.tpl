<?php echo $header; ?>
<style type="text/css">
  .loader{
    position: absolute;
    background-color: #000;
    opacity: 0.6;
    width: 100%;
    z-index: 9999;
    display: none;
  }
</style>
<div class="container">
<ul class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
<?php $i=1; foreach ($breadcrumbs as $breadcrumb) { ?>
  <li itemprop="itemListElement" itemscope
  itemtype="https://schema.org/ListItem">
  <a href="<?php echo $breadcrumb['href']; ?>" itemprop="name"><?php echo $breadcrumb['text']; ?></a><span>/</span>
  <meta itemprop="position" content="<?=$i?>" />
  </li>
<?php $i++; } ?>
</ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($description_top) { ?>
      <div class="row dt">
        <?php if ($description_top) { ?>
        <div class="col-sm-10"><?php echo $description_top; ?></div>
        <?php } ?>
      </div>
      <hr>
      <?php } ?>
      <?php if ($products) { ?>
      <p><a href="<?php echo $compare; ?>" id="compare-total"><?php echo $text_compare; ?></a></p>
      <div class="row">
        <div class="col-md-4">
          <div class="btn-group hidden-xs">
            <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
            <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
          </div>
        </div>
        <div class="col-md-2 text-right">
          <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
        </div>
        <div class="col-md-3 text-right">
          <select id="input-sort" class="form-control" onchange="location = this.value;">
            <?php foreach ($sorts as $sorts) { ?>
            <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
            <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-1 text-right">
          <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
        </div>
        <div class="col-md-2 text-right">
          <select id="input-limit" class="form-control" onchange="location = this.value;">
            <?php foreach ($limits as $limits) { ?>
            <?php if ($limits['value'] == $limit) { ?>
            <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <br />
      <div class="row">
        <?php foreach ($products as $product) { ?>
        <div class="product-layout product-list col-xs-12">
          <div class="product-thumb">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img class="lazy" data-src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div>
              <div class="caption">
                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                <p><?php echo $product['description']; ?></p>
                <?php if ($product['rating']) { ?>
                <div class="rating">
                  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>
                <?php if ($product['price']) { ?>
                <p class="price">
                  <?php if (!$product['special']) { ?>
                  <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                  <?php } ?>
                  <?php if ($product['tax']) { ?>
                  <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                  <?php } ?>
                </p>
                <?php } ?>
              </div>
              <div class="button-group">
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
              </div>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
      <?php } ?>
      <?php if (!$products) { ?>
      <p><?php echo $text_empty; ?></p>
      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
      </div>
      <?php } ?>
      <?php if ($description_bottom) { ?>
        <hr>
        <div class="row db">
          <?php if ($description_bottom) { ?>
          <div class="col-sm-10"><?php echo $description_bottom; ?></div>
          <?php } ?>
        </div>
      <?php } ?>
      <?php if ($tags) { ?>
      <p><?php echo $text_tags; ?>
        <?php for ($i = 0; $i < count($tags); $i++) { ?>
        <?php if ($i < (count($tags) - 1)) { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
        <?php } else { ?>
        <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
        <?php } ?>
        <?php } ?>
      </p>
      <?php } ?>
      <?php echo $content_bottom; ?>
    </div>

    <?php echo $column_right; ?></div>
</div>
<?php if ($ajax) { ?>
<script type="text/javascript">
$(function(){
  $('body').prepend('<div class="loader"></div>');
})
enable_a();
var pp = "<?php echo $page; ?>";
var cp = "<?php echo $countp; ?>";
function enable_a(){
  $(".pagination a").click(function(){ 
    var page = $(this).text();
    if (page != '|<' && page != '<' && page != '>' && page != '>|'){
      pp = page;
    }
    else{
      switch(page){
        case '<' : page = parseInt(pp)-1; break;
        case '>' : page = parseInt(pp)+1; break;
        case '|<' : page = 1; break;
        case '>|' : page = cp; break;
      }
      pp = page;
    }
    var tag_id = "<?php echo $tag_id; ?>";
    var sort = "<?php echo $sort; ?>";
    var limit = "<?php echo $limit; ?>";
    var order = "<?php echo $order; ?>";
    window.history.pushState("", "", $(this).attr('href'));
    $(".loader").height($("body").height()+"px");
    $(".loader").show(); 
    $.ajax({
      url: 'index.php?route=product/tags/ajax',
      type: 'post',
      data: 'tag_id='+tag_id+'&page='+page+'&sort='+sort+'&order='+order+'&limit='+limit,
      dataType: 'json',
      success: function(json) {
        text = json;
        $("#content").empty();
        $("#content").append(text);
        $('html, body').animate({ scrollTop: 0 }, 'slow'); 
        $(".loader").hide();
        enable_a();
      },
      error: function(jqXHR,textStatus,errorThrown){
        text = jqXHR.responseText;
        $("#content").empty();
        $("#content").append(text);
        $('html, body').animate({ scrollTop: 0 }, 'slow'); 
        $(".loader").hide();
        enable_a();
      }
    });
    return false;
  });
}
</script>
<?php } ?>
<?php echo $footer; ?>
