<div class="col-xs-12 col-sm-6">
  <table class="table table-bordered table-hover custom-table">
    <thead>
    <th colspan="2">Соискатели</th>
    </thead>
    <tbody>
    <tr>
      <td>Ссылка AB тестирования</td>
      <td><?=$data['applicant']['link']?></td>
    </tr>
    <tr>
      <td>Всего переходов по ссылке</td>
      <td><?=$data['applicant']['cnt']?></td>
    </tr>
    </tbody>
  </table>
</div>
<div class="col-xs-12 col-sm-6">
  <table class="table table-bordered table-hover custom-table">
    <thead>
    <th colspan="2">Работодетали</th>
    </thead>
    <tbody>
    <tr>
      <td>Ссылка AB тестирования</td>
      <td><?=$data['employer']['link']?></td>
    </tr>
    <tr>
      <td>Всего переходов по ссылке</td>
      <td><?=$data['employer']['cnt']?></td>
    </tr>
    </tbody>
  </table>
</div>
<div class="col-xs-12">
  <table class="table table-bordered table-hover custom-table">
    <thead>
    <th>Страница</th>
    <th>Кликов (переходы на данные страницы)</th>
    <th>Лидов</th>
    <th>Конверсия</th>
    </thead>
    <tbody>
    <? foreach ($data['all'] as $v): ?>
      <tr>
        <td><?=$v['page']?></td>
        <td><?=$v['cnt']?></td>
        <td><?=$v['cnt_lead']?></td>
        <td><?=$v['conversion']?> %</td>
      </tr>
    <? endforeach; ?>
    </tbody>
  </table>
</div>