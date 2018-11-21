<?
    Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/templates.js', CClientScript::POS_HEAD);
    $model = new FeedbackTemplate();
    $arTemplates = $model->getTemplates();
?>
<div class="col-md-6 col-xs-12">
    <h3>Шаблоны</h3>

    <div class="control-group">
        <label class="control-label">Название шаблона</label>
        <div class="controls input-append">
            <input class="form-control" id="template_title" type="text" name="template_title">
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Текст шаблона</label>
        <textarea rows="6" cols="50" class="form-control" id="template_description" name="template_description"></textarea>
        <p></p>
    </div>

    <div class="templates__control">
        <div class="btn label-success" id="save-template">Сохранить</div>
    </div>

    <div class="control-group">
        <label class="control-label">Шаблоны:</label>
        <div class="templates__block">
            <? foreach ($arTemplates as $v): ?>
                <div data-id="<?=$v['id']?>" class="template__item">
                    <?=$v['name']?>
                    <b>x</b>
                    <i><?=$v['text']?></i>
                </div>
            <? endforeach; ?>
        </div>
    </div>

</div>
<style type="text/css">
    #admin-answer{ min-height: 200px }
    .hidd {
        visibility: hidden;
    }
    .templates__control{
        text-align: right;
    }
    .btn-success-template{
        background-color: #00a65a;
        border-color: #008d4c;
        color: white;
        margin-top: 10px;
        display: inline;
    }
    .templates__block{
        transform: translate(0, 0);
        padding: 10px;
        height: 250px;
        overflow: auto;
        background: white;
    }
    .template__item{
        font-size: 16px;
        color: black;
        padding: 4px;
        cursor: pointer;
        padding-right: 27px;
        position: relative;
    }
    .template__item:hover{
        background-color: #f39c12;
    }
    .content-wrapper{
        overflow: hidden;
    }
    .template__item b{
        display: block;
        position: absolute;
        top: 4px;
        right: 13px;
        width: 20px;
        text-align: center;
        color: red;
    }
    .template__item i{ display: none; }
    #Update_message{
        height: 200px;
    }
    .direct-chat-text.chat-admin{
        margin: 5px 0 0 50px;
        background: #dce57e;
        border: 1px solid #dce57e;
    }
    .direct-chat-text.chat-admin:after, .direct-chat-text.chat-admin:before{
        border-right-color: #dce57e;
    }

    .direct-chat-text{
        margin: 5px 50px 0 0;
    }

</style>