<div><a href="<?= $viData['loginUrl'] ?>">Авторизироваться в ВК</a></div>

<?php if( $viData['loginUrlGroups'] ): ?>
    <div><a href="<?= $viData['loginUrlGroups'] ?>">Получить доступ к руппам для постинга</a></div>
<?php endif; ?>

<?php if( $viData['accessToken'] ): ?>
    <div>Access token: <?= $viData['accessToken'] ?></div>
<?php endif; ?>

<?php if( $viData['groups'] ): ?>
    <div>
        Groups:
        <?php $flag = 0; foreach ($viData['groups'] as $key => $val): ?><?php $flag ? (print ', ') : $flag = 1; ?>
            <a href="//vk.com/club<?= $val ?>" target="_blank"><?= $val ?></a>
        <?php endforeach; ?>
    </div>

    <div><a href="?testpost=1">Тестовый пост на стену</a></div>
<?php endif; ?>