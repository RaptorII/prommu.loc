<?
  $bUrl = Yii::app()->request->baseUrl;
  $gcs = Yii::app()->getClientScript();
  $gcs->registerCssFile($bUrl . '/css/template.css');
  $gcs->registerScriptFile($bUrl . '/js/nicEdit.js', CClientScript::POS_HEAD);
  $vacancy = $viData['item'];
?>
<div class="row vacancy__item">
  <?if(!is_array($vacancy)):?>
    <div class="col-xs-12">
      <div class="alert danger">Данные отсутствуют</div>
    </div>
  <?else:?>
    <div class="col-xs-12">
      <h3>Редактирование вакансии №<?=$viData['id']?></h3>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-2">
      <ul class="nav user__menu" role="tablist" id="tablist">
        <li class="active">
          <a href="#tab_main" aria-controls="tab_main" role="tab" data-toggle="tab">Общее</a>
        </li>
        <li>
          <a href="#tab_descr" aria-controls="tab_descr" role="tab" data-toggle="tab">Описание</a>
        </li>
        <li>
          <a href="#tab_index" aria-controls="tab_index" role="tab" data-toggle="tab">Адрес и время работы</a>
        </li>
        <li>
          <a href="#tab_services" aria-controls="tab_services" role="tab" data-toggle="tab">Услуги</a>
        </li>
        <li>
          <a href="#tab_seo" aria-controls="tab_seo" role="tab" data-toggle="tab">SEO</a>
        </li>
      </ul>
    </div>
    <?
    // content
    ?>
    <div class="col-xs-12 col-sm-6 col-md-8">
      <? echo CHtml::form($id,'post',['class'=>'form-horizontal']); ?>
        <div class="tab-content">
          <?
          // Main
          ?> 
          <div role="tabpanel" class="tab-pane fade active in" id="tab_main">
            <div class="row">
              <div class="col-xs-12 col-md-6">
                <div class="d-indent">
                  <span>Дата создания:</span> <b><?=date('H:i d.m.y',$vacancy['crdate'])?></b>
                </div>
                <div class="d-indent">
                  <span>Дата модерации:</span> <b><?=date('H:i d.m.y',$vacancy['mdate'])?></b>
                </div>
                <div class="d-indent">
                  <span>Дата начала работ:</span> <b><?=date('d.m.y',$viData['dates']['bdate'])?></b>
                </div>
                <div class="d-indent">
                  <span>Дата завершения работ:</span> <b><?=date('d.m.y',$vacancy['remdate'])?></b>
                </div>
              </div>
              <div class="col-xs-12 col-md-6">
                <div class="d-indent">
                  <span>Просмотров:</span> <b><?=$viData['views']?></b>
                </div>
                <div class="d-indent">
                  <span>Откликов:</span> <b><?=count($viData['responses']['items'])?></b>
                  <? if(count($viData['responses']['items'])): ?>
                    <div>
                      <?
                        $arRes = [];
                        foreach ($viData['responses']['items'] as $v)
                        {
                          $arUser = $viData['responses']['users'][$v['id_user']];
                          $arRes[] = '<a href="/admin/PromoEdit/' . $v['id_user'] . '">' . $arUser['name'] . '</a>';
                        }
                        echo implode(', ', $arRes);
                      ?>
                    </div>
                  <? endif; ?>
                </div>
                <div class="d-indent">
                  <span>Утвержденных:</span> <b><?=count($viData['responses']['approved'])?></b>
                  <? if(count($viData['responses']['approved'])): ?>
                    <div>
                      <?
                        $arRes = [];
                        foreach ($viData['responses']['approved'] as $v)
                        {
                          $arUser = $viData['responses']['users'][$v['id_user']];
                          $arRes[] = '<a href="/admin/PromoEdit/' . $v['id_user'] . '">' . $arUser['name'] . '</a>';
                        }
                        echo implode(', ', $arRes);
                      ?>
                    </div>
                  <? endif; ?>
                </div>
              </div>
            </div>
            <div class="row">
              <?
              //
              ?>
              <div class="col-xs-12 col-md-6">
                <h4>Общее</h4>
                <label class="d-label">
                  <span>Название</span>
                  <? echo CHtml::textField('Vacancy[title]', $vacancy['title'], ['class'=>'form-control']); ?>
                </label> 
                <div class="d-indent">
                  <span>Должности:</span><br><b><?=implode(', ',$vacancy['post'])?></b>
                </div>
              </div>
              <?
              //
              ?>
              <div class="col-xs-12 col-sm-6">
                <h4>Оплата за проект</h4>
                <div class="row">
                  <div class="col-xs-6">
                    <label class="d-label">
                      <span>за час</span>
                      <? echo CHtml::textField('Vacancy[shour]', $vacancy['shour'], ['class'=>'form-control']); ?>
                    </label>
                    <label class="d-label">
                      <span>за неделю</span>
                      <? echo CHtml::textField('Vacancy[sweek]', $vacancy['sweek'], ['class'=>'form-control']); ?>
                    </label>
                  </div>
                  <div class="col-xs-6">
                    <label class="d-label">
                      <span>за месяц</span>
                      <? echo CHtml::textField('Vacancy[smonth]', $vacancy['smonth'], ['class'=>'form-control']); ?>
                    </label>
                    <label class="d-label">
                      <span>за посещение</span>
                      <? echo CHtml::textField('Vacancy[svisit]', $vacancy['svisit'], ['class'=>'form-control']); ?>
                    </label>
                  </div>
                </div>
                <div class="d-indent">
                  <span>Сроки оплаты: <b><?=$viData['properties']['paylims']['dname']?></b></span>
                </div>
              </div>
              <?
              //
              ?>
              <div class="col-xs-12 col-md-6">
                <h4>Кто нужен</h4>
                <div class="d-indent">
                  <span>Опыт работы: <b><?=Vacancy::$EXPERIENCE[$vacancy['exp']]?></b></span>
                </div>
                <div class="row">
                  <div class="col-xs-12 col-sm-6">
                    <span>Пол: </span>
                    <label class="d-label">
                      <span>Мужской</span>
                      <? echo CHtml::CheckBox('Vacancy[isman]',$vacancy['isman'],['value'=>'1']); ?>
                    </label>
                    <label class="d-label">
                      <span>Женский</span>
                      <? echo CHtml::CheckBox('Vacancy[iswoman]',$vacancy['iswoman'],['value'=>'1']); ?>
                    </label>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <span>Вид занятости:</span>
                    <label class="d-label">
                      <span>временная</span>
                      <input type="radio" name="Vacancy[istemp]" value="0" <?=!$vacancy['istemp'] ? 'checked' : ''?>>
                    </label>
                    <label class="d-label">
                      <span>постоянная</span>
                      <input type="radio" name="Vacancy[istemp]" value="1" <?=$vacancy['istemp'] ? 'checked' : ''?>>
                    </label>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <span>Дополнительно: </span>
                    <label class="d-label">
                      <span>Медкнижка</span>
                      <? echo CHtml::CheckBox('Vacancy[ismed]',$vacancy['ismed'],['value'=>'1']); ?>
                    </label>
                    <label class="d-label">
                      <span>Автомобиль</span>
                      <? echo CHtml::CheckBox('Vacancy[isavto]',$vacancy['isavto'],['value'=>'1']); ?>
                    </label>
                    <label class="d-label">
                      <span>Смартфон</span>
                      <? echo CHtml::CheckBox('Vacancy[smart]',$vacancy['smart'],['value'=>'1']); ?>
                    </label>
                    <label class="d-label">
                      <span>Карта</span>
                      <? echo CHtml::CheckBox('Vacancy[card]',$vacancy['card'],['value'=>'1']); ?>
                    </label>
                    <label class="d-label">
                      <span>Карта Prommu</span>
                      <? echo CHtml::CheckBox('Vacancy[cardPrommu]',$vacancy['cardPrommu'],['value'=>'1']); ?>
                    </label>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <span>Возраст: </span>
                    <label class="d-label">
                      <span>от</span>
                      <input type="number" name="Vacancy[agefrom]" value="<?=$vacancy['agefrom']?>" class="form-control">
                    </label>
                    <label class="d-label">
                      <span>до</span>
                      <input type="number" name="Vacancy[ageto]" value="<?=$vacancy['ageto']?>" class="form-control">
                    </label>
                  </div>
                </div>
              </div>
              <?
              //
              ?>
              <div class="col-xs-12 col-md-6">
                <h4>Модерация</h4>
                <label class="d-label">
                  <span>Активность вакансии</span>
                  <? echo CHtml::CheckBox('Vacancy[status]', $vacancy['status'], ['value'=>'1']); ?>
                </label>
                <label class="d-label">
                  <span>Проверено администратором</span>
                  <? echo CHtml::CheckBox('Vacancy[ismoder]', $vacancy['ismoder'], ['value'=>'100']); ?>
                </label>
                <label class="d-label">
                  <span>Комментарий администратора</span>
                  <div id="panel_comment"></div>
                  <textarea name="Vacancy[comment]" class="d-textarea" id="area_comment"><?=$vacancy['comment']?></textarea>
                </label>
              </div>
            </div>
          </div>
          <?
          // Описание
          ?> 
          <div role="tabpanel" class="tab-pane fade" id="tab_descr">
            <h4>Описание работы</h4>
            <label class="d-label">
              <span>Требования</span>
              <div id="panel_requirements"></div>
              <textarea name="Vacancy[requirements]" class="d-textarea" id="area_requirements"><?=$vacancy['requirements']?></textarea>
            </label>
            <label class="d-label">
              <span>Обязанности</span>
              <div id="panel_duties"></div>
              <textarea name="Vacancy[duties]" class="d-textarea" id="area_duties"><?=$vacancy['duties']?></textarea>
            </label>
            <label class="d-label">
              <span>Условия</span>
              <div id="panel_conditions"></div>
              <textarea name="Vacancy[conditions]" class="d-textarea" id="area_conditions"><?=$vacancy['conditions']?></textarea>
            </label>
          </div>
          <?
          //
          ?> 
          <div role="tabpanel" class="tab-pane fade" id="tab_index">
            <h4>Адрес и время работы</h4>
            <div class="vacancy__loc">
              <ul>
                <? $count = 1; ?>
                <? foreach ($viData['cities'] as $c): ?>
                  <li>
                    <div>Город <?=$count?>: <b><?=$c['city']?></b></div>
                    <? // цикл по локациям ?>
                    <ul class="vacancy__city">
                      <? foreach ($viData['locations'] as $l): ?>
                        <? if($l['id_city']==$c['id']): ?>
                          <li>
                            <table>
                              <tr>
                                <td>Название локации: </td>
                                <td><b><?=$l['name']?></b></td>
                              </tr>
                              <tr>
                                <td>Адрес локации: </td>
                                <td><b><?=$l['addr']?></b></td>
                              </tr>
                              <? if (count($l['metro'])): ?>
                                <tr>
                                  <td>Метро: </td>
                                  <td><b><?=implode(', <br>', $l['metro'])?></b></td>
                                </tr>
                              <? endif; ?>
                            </table>
                            <? // цикл по периодам ?>
                            <ul class="vacancy__location">
                              <? if(count($l['periods'])): ?>
                                <? foreach ($l['periods'] as $p): ?>
                                  <li>
                                    <?
                                      $sDate = '';
                                      $curY = date('Y');
                                      $bdate = (date('Y',$p['bdate'])==$curY ? date('d.m',$p['bdate']) : date('d.m.y',$p['bdate']));
                                      $edate = (date('Y',$p['edate'])==$curY ? date('d.m',$p['edate']) : date('d.m.y',$p['edate']));
                                      $sDate = ($p['bdate']!=$p['edate'] ? "c $bdate по $edate" : $bdate);
                                      $sDate .= ' ' . $p['btime'] . '-' . $p['etime'];
                                    ?>
                                    <div>Дата работы на проекте: <b><?=$sDate?></b></div>
                                  </li>
                                <? endforeach; ?>
                              <? endif; ?>
                            </ul>
                          </li>
                        <? endif; ?>
                      <? endforeach; ?>
                    </ul>
                  </li>
                  <? $count++ ?>
                <? endforeach; ?>
              </ul>
            </div>
          </div>
          <?
          // Услуги
          ?> 
          <div role="tabpanel" class="tab-pane fade" id="tab_services">
            <? $serviceCnt = 0; ?>
            <h4>Данные по услугам</h4>
            <label class="d-label">
              <span>Премиум</span>
              <? echo CHtml::CheckBox('Vacancy[ispremium]',$vacancy['ispremium'],['value'=>'1']); ?>
              <? $vacancy['ispremium'] && $serviceCnt++; ?>
            </label>
            <label class="d-label">
              <span>Email - <?=count($viData['services']['email']['items'])?></span>
              <? if(count($viData['services']['email']['items'])): ?>
                (успешно отправлено - <?=$viData['services']['email']['good_status']?>)
              <? endif; ?>
            </label>
            <? if(count($viData['services']['email']['items'])): ?>
              <div class="services__list">
                <? foreach ($viData['services']['email']['items'] as $k => $v): ?>
                  <? $arUser = $viData['services']['users'][$v['user']]; ?>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a href="/admin/PromoEdit/<?=$arUser['id']?>" target="_blank">
                      <img src="<?=$arUser['src']?>">
                      <span><?=$arUser['name']?></span>
                    </a>
                  </div>
                <? endforeach; ?>
              </div>
              <? $serviceCnt++; ?>
            <? endif; ?>

            <label class="d-label">
              <span>Push - <?=count($viData['services']['push']['items'])?></span>
              <? if(count($viData['services']['push']['items'])): ?>
                (успешно отправлено - <?=$viData['services']['push']['good_status']?>)
              <? endif; ?>
            </label>
            <? if(count($viData['services']['push']['items'])): ?>
              <div class="services__list">
                <? foreach ($viData['services']['push']['items'] as $k => $v): ?>
                  <? $arUser = $viData['services']['users'][$v['user']]; ?>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a href="/admin/PromoEdit/<?=$arUser['id']?>" target="_blank">
                      <img src="<?=$arUser['src']?>">
                      <span><?=$arUser['name']?></span>
                    </a>
                  </div>
                <? endforeach; ?>
              </div>
              <? $serviceCnt++; ?>
            <? endif; ?>

            <label class="d-label">
              <span>SMS - <?=count($viData['services']['sms']['items'])?></span>
              <? if(count($viData['services']['sms']['items'])): ?>
                (успешно отправлено - <?=$viData['services']['sms']['good_status']?>)
              <? endif; ?>
            </label>
            <? if(count($viData['services']['sms']['items'])): ?>
              <div class="services__list">
                <? foreach ($viData['services']['sms']['items'] as $k => $v): ?>
                  <? $arUser = $viData['services']['users'][$v['user']]; ?>
                  <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                    <a href="/admin/PromoEdit/<?=$arUser['id']?>" target="_blank">
                      <img src="<?=$arUser['src']?>">
                      <span><?=$arUser['name']?></span>
                    </a>
                  </div>
                <? endforeach; ?>
              </div>
              <? $serviceCnt++; ?>
            <? endif; ?>

            <label class="d-label">
              <span>Репост в ВК</span>
              <span class="glyphicon glyphicon-<?=(substr($vacancy['repost'],0,1)=='1')?'check':'unchecked'?>"></span>
              <? if (!empty($vacancy['vk_link'])): ?>
                <a href="<?=$vacancy['vk_link']?>" target="_blank" class="glyphicon glyphicon-link"></a>
                <? $serviceCnt++; ?>
              <? endif; ?>
            </label>

            <label class="d-label">
              <span>Репост в Facebook</span>
              <span class="glyphicon glyphicon-<?=(substr($vacancy['repost'],1,1)=='1')?'check':'unchecked'?>"></span>
              <? if (!empty($vacancy['fb_link'])): ?>
                <a href="<?=$vacancy['fb_link']?>" target="_blank" class="glyphicon glyphicon-link"></a>
                <? $serviceCnt++; ?>
              <? endif; ?>
            </label>

            <label class="d-label">
              <span>Репост в Telegram</span>
              <span class="glyphicon glyphicon-<?=(substr($vacancy['repost'],2,1)=='1')?'check':'unchecked'?>"></span>
              <? if (!empty($vacancy['tl_link'])): ?>
                <a href="<?=$vacancy['tl_link']?>" target="_blank" class="glyphicon glyphicon-link"></a>
                <? $serviceCnt++; ?>
              <? endif; ?>
            </label>
            <a href="/admin/services?Service[name]=<?=$viData['id']?>" target="_blank">Транзакции по вакансии</a>
          </div>
          <?
          // SEO
          ?>  
          <div role="tabpanel" class="tab-pane fade" id="tab_seo">
            <h4>SEO</h4>
            <label class="d-label">
              <span>Запретить индексацию</span>
              <? echo CHtml::CheckBox('Vacancy[index]',$vacancy['index'],['value'=>'1']); ?>
            </label>
            <label class="d-label">
              <span>Title</span>
              <div class="d-indent"><?=$viData['seo']['meta_title']?></div>
            </label>
            <label class="d-label">
              <span>H1 заголовок</span>
              <div class="d-indent"><?=$viData['seo']['meta_h1']?></div>
            </label>
            <label class="d-label">
              <span>Description</span>
              <div class="d-indent"><?=$viData['seo']['meta_description']?></div>
            </label>
          </div>
        </div>
        <?
        //
        ?>
        <div class="col-xs-12">
          <div class="pull-right">
            <a href="/admin/vacancy" class="btn btn-success d-indent">Назад</a>
            <button type="submit" class="btn btn-success d-indent" id="btn_submit">Сохранить</button>
          </div>
        </div>
      <? echo CHtml::endForm(); ?>
    </div>
    <div class="col-xs-12 col-sm-3 col-md-2"></div>
    <script type="text/javascript">
      'use strict'
      var nicEditorParams = {
              maxHeight: 200, 
              buttonList: ['bold','italic','underline','left','center','right','justify','ol','ul'] 
            },
          nicEditorReq = new nicEditor(nicEditorParams),
          nicEditorDuties = new nicEditor(nicEditorParams),
          nicEditorCond = new nicEditor(nicEditorParams),
          nicEditorComment = new nicEditor(nicEditorParams);

      jQuery(function($){
        nicEditorReq.addInstance('area_requirements');
        nicEditorReq.setPanel('panel_requirements');
        nicEditorDuties.addInstance('area_duties');
        nicEditorDuties.setPanel('panel_duties');
        nicEditorCond.addInstance('area_conditions');
        nicEditorCond.setPanel('panel_conditions');
        nicEditorComment.addInstance('area_comment');
        nicEditorComment.setPanel('panel_comment');
      });
    </script>
  <? endif; ?>
</div>
<style type="text/css">
  .vacancy__loc ul{
    list-style: none;
    padding-left: 15px;
  }
  .vacancy__loc li{
    border-bottom: 1px solid #c5c5c5;
    padding-bottom: 5px;
    margin-bottom: 5px;
  }
  .app_project__location{
    border-top: 1px solid #c5c5c5;
    padding-top: 5px;
    margin-top: 5px;
  }
  .vacancy__loc li:last-child{ border: none }
  .vacancy__city td{ padding: 5px; }
  .vacancy__item h4{
    margin: 30px 0 10px; 
    text-transform: uppercase;
    font-size: 20px;
  }
  #panel_requirements + div,
  #panel_duties + div,
  #panel_conditions + div{ 
    width: 100%!important;
    min-height: 200px!important;
  }
  .nicEdit-main{ min-height: 198px!important; }
  .services__list{
    margin: 10px -15px 30px;
  }
  .services__list a{
    padding: 0 0 20px;
    display: block;
    text-align: center;
    font-size: 18px;
  }
  .services__list img{
    width: 100%;
    border-radius: 50%;
    padding-bottom: 10px;
  }
</style>