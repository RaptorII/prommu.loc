<!DOCTYPE html>
<html lang="ru">
  <head>
    <title>Счет на оплату</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  </head>
  <style>
    .payment{
      width: 800px;
      margin: 0 auto 50px;
      font-family: sans-serif;
    }
    .payment table{
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 15px;
    }
    .payment td{ padding: 5px; }
    .payment-top td,
    .payment-service td{ border: 1px solid #000000; }
    .payment hr{
      width: 100%;
      height: 2px;
      border: none;
      background-color: #000000;
    }
    .payment-service{ border: 2px solid #000000; }
    .payment-total{ text-align: right; }
    .payment-total td:first-child{ width: 80%; }
    .payment-bottom td:first-child,
    .payment-bottom td:nth-child(3){
      width: 15%;
      text-align: right;
    }
    .payment-bottom td:nth-child(2),
    .payment-bottom td:last-child{
      width: 35%;
      text-align: center;
      border-bottom: 1px solid #000000;
    }
  </style>
  <body>
  <?
  /*
  echo '<pre>';
  print_r($viData);
  echo '</pre>';
  */
  ?>


    <div class="payment">
      <table class="payment-top">
        <tr>
          <td rowspan="2" colspan="2">
            ПАО СБЕРБАНК Г. МОСКВА<br><br><small>Банк получателя</small>
          </td>
          <td>БИК</td>
          <td rowspan="2">044525225<br><br>30101810400000000225</td>
        </tr>
        <tr>
          <td>Сч. №</td>
        </tr>
        <tr>
          <td>ИНН 7722456405</td>
          <td>КПП   772201001</td>
          <td rowspan="2">Сч. №</td>
          <td rowspan="2">40702810638000082316</td>
        </tr>
        <tr>
          <td colspan="2">ООО "РАЗГРУЗОЧНО-ТРАНСПОРТНАЯ КОМПАНИЯ"<br><br><small>Получатель</small></td>
        </tr>
      </table>
      <h1>Счет на оплату № <?=$viData['id']?> от <?=$viData['date1']?></h1>
      <hr>
      <table class="payment-index">
        <tr>
          <td>Поставщик (Исполнитель):</td>
          <td><b>ООО "РАЗГРУЗОЧНО-ТРАНСПОРТНАЯ КОМПАНИЯ", ИНН 7722456405, КПП
            772201001, 109052, Москва г, Нижегородская ул, дом № 104, корпус 3, этаж П П I,
              ком. 11, оф.13</b></td>
        </tr>
        <tr>
          <td>Покупатель (Заказчик):</td>
          <td><b>"<?=$viData['company']?>", ИНН <?=$viData['inn']?>, КПП <?=$viData['kpp']?>, <?=$viData['index']?></b></td>
        </tr>
        <tr>
          <td>Основание:</td>
          <td><b>ДОГОВОР ОБ ОКАЗАНИИ УСЛУГ № <?=$viData['date2']?> ВН ОТ <?=$viData['date3']?></b></td>
        </tr>
      </table>

      <table class="payment-service">
        <tr>
          <td><b>№</b></td>
          <td><b>Товары (работы, услуги)</b></td>
          <td><b>Кол-во</b></td>
          <td><b>Ед.</b></td>
          <td><b>Цена</b></td>
          <td><b>Сумма</b></td>
        </tr>
        <? $cnt = 1; ?>
        <? foreach ($viData['services'] as $v): ?>
          <tr>
            <td><?=$cnt?></td>
            <td><?=$v['title']?></td>
            <td></td>
            <td>шт</td>
            <td align="right"><?=$v['cost']?></td>
            <td align="right"><?=$v['cost']?></td>
          </tr>
          <? $cnt++; ?>
        <? endforeach; ?>
      </table>

      <table class="payment-total">
        <tr>
          <td><b>Итого:</b></td>
          <td><b><?=$viData['cost']?></b></td>
        </tr>
        <? if($viData['with_nds']): ?>
          <tr>
            <td><b>В том числе НДС:</b></td>
            <td><b><?=$viData['nds']?></b></td>
          </tr>
        <? endif; ?>
        <tr>
          <td><b>Всего к оплате:</b></td>
          <td><b><?=$viData['total_cost']?></b></td>
        </tr>
      </table>

      Всего наименований <?=count($viData['services'])?>, на сумму <?=$viData['total_cost']?> руб.<br>
      <b><?=$viData['total_cost_str']?></b><br><br>
      Оплатить не позднее <?=$viData['last_date']?><br>
      Оплата данного счета означает согласие с условиями поставки товара.<br>
      Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе.<br>
      Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и
      паспорта.
      <hr>
      <table class="payment-bottom">
        <tr>
          <td><b>Руководитель</b></td>
          <td>Беличенко А. Н.</td>
          <td><b>Бухгалтер</b></td>
          <td></td>
        </tr>
      </table>
    </div>
  </body>
</html>