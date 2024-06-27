<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Запрос цены</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
    <div>Имя: <?php echo $name ?></div>
    <div>Телeфон: <?php echo $ft ?><? echo $code ?><? echo $phone ?></div>
    <div>Email: <?php echo $email ?></div>
    <?php if(isset($region)) { ?>
        <div>Регион: <?php echo $region ?></div>
    <?php } ?>
    <?php if(isset( $firma )){ ?>
        <div><?php echo $firma ?></div>
    <?php } ?>
    <?php if(isset($amount)) { ?>
        <div>Количество автоматов: <?php echo $amount ?></div>
    <?php } ?>
    <div>
        <?php if(isset($credit) && $credit) { ?>
            Интерисует кредит/лизинг
        <?php } ?>
    </div>
    <div>
        <?php if(isset($has)) { ?>
            Уже есть автоматы: <?php echo $has; ?>
        <?php } ?>
    </div>
    <?php if(isset($note)) { ?>
    <div>
        <?php echo $note; ?>
    </div>
    <?php } ?>
    <?php if(isset($url)) { ?>
    <div>
        Отправлено со страницы <?php echo $url ?>
    </div>
    <?php } ?>
    <?php if(isset($product)){ ?>
        <div>Товар: <?php echo $product ?></div>
    <?php }?>

    <? if(isset($opts)) { ?>
        <div>Комплектация: <? echo $opts ?></div>
    <? } ?>
</body>
</html>
