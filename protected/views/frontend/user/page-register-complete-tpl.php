<div class='row'>
    <script type="text/javascript">
        <!--
        $(document).ready(function()
        {
            var type = '<?= $type ?>';

            if ( parseInt(type) == 1 ) type = 'Соискатели';
            else type = 'Работодатели';
                window.dataLayer = window.dataLayer || [];
                dataLayer.push({
                    'event': type
                });
        });
        //-->
    </script>

    <div class='col-xs-12 register-wrapp'>
        <div class="complete-block">
            Уважаемый пользователь, для подтверждения регистрации Вам необходимо зайти в свою электронную почту
            (указанную при регистрации) и сделать переход по активной ссылке. После этого действия Ваша анкета будет
            активирована.
            <br>Если письмо долго не приходит - проверьте папку спам, так как почтовый сервер может быть череcчур
            бдительным.
            <br/>
            <br/>
            Спасибо за Ваш выбор!
        </div>
    </div>
</div>