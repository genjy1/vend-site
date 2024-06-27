<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>История уведомлений</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> Список уведомлений</h3>
      </div>
      <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data" id="form-group">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left">
                    Id
                  </td>
                  <td>
                    Изображение
                  </td>
                  <td>
                    Заголовок
                  </td>
                  <td>
                    Url
                  </td>
                  <td>
                    Сообщение
                  </td>
                  <td>
                    Дата
                  </td>
                  <td>
                    Статус
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php if ($notifications) { ?>
                <?php foreach ($notifications as $notification) { ?>
                <tr>
                  <td><?php echo $notification['id'] ?></td>
                  <td><img src="<?php echo $notification['icon'] ?>" alt=""></td>
                  <td><?php echo $notification['title'] ?></td>
                  <td><a href="<?php echo $notification['url'] ?>" target="_blank"><?php echo $notification['url'] ?></a></td>
                  <td><?php echo $notification['body'] ?></td>
                  <td><?php echo $notification['date_added'] ?></td>
                  <td><?php if($notification['status'] ) { ?>
                      Отправлено успешно
                    <?php } else { ?>
                      Оштбка при отправлении
                    <?php } ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  var url = 'index.php?route=extension/sub&token=<?php echo $token; ?>';

  var filter = $('input[name=\'filter\']').val();

  if (filter) {
    url += '&filter=' + encodeURIComponent(filter);
  }

  location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter\']').autocomplete({
  'source': function(request, response) {
    $.ajax({
      url: 'index.php?route=extension/sub/autocomplete&token=<?php echo $token; ?>&filter=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: item['name'],
          }
        }));
      }
    });
  },
  'select': function(item) {
    $('input[name=\'filter\']').val(item['label']);
  }
});
//--></script>
<?php echo $footer; ?> 