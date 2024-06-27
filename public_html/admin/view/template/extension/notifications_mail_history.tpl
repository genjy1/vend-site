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
                    Тема
                  </td>
                  <td>
                    Сообщение
                  </td>
                  <td>
                    Дата
                  </td>
                  <td>
                    Доставлено
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php if ($notifications) { ?>
                <?php foreach ($notifications as $notification) { ?>
                <tr>
                  <td><?php echo $notification['id'] ?></td>
                  <td><?php echo $notification['subject'] ?></td>
                  <td><?php echo $notification['message'] ?></td>
                  <td><?php echo $notification['date_added'] ?></td>
                  <td><?php echo $notification['count'] ?> подписчикам</td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5">Нет уведомлений</td>
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
<?php echo $footer; ?> 