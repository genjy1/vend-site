  <div class="homenews">
    <h2>Новости</h2>
    <a href="/index.php?route=post/posts&post_category_id=15" class="allnews">Все новости</a>
    <div class="news">
    <? foreach($posts as $post){ ?>
      <div class="block">
        <a href="<? echo $post['href'] ?>">
          <div class="img"><img src="<? echo $post['thumb'] ?>" alt=""><div class="pol"></div></div>
          <div class="text">
            <div class="date"><? echo $post['date'] ?></div>
            <div class="title"><? echo $post['title'] ?></div>
          </div>
        </a>
      </div>
      <? } ?>
    </div>
  </div>