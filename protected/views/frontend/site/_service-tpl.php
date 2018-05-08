<?php if( $level == 1 ): ?>
    <div class='header-022'>
      <div>
        <a class='anc' name='<?= $viData['services'][$id]['link'] ?>'></a><a href="<?= $url ?>"><?= $viData['services'][$id]['name'] ?></a>
      </div>
    </div>
    <br />
<?php endif; ?>
<div class="service">
    <div class="img"><img src="/images/servicesgr/<?= $viData['services'][$id]['imganons'] ?>" alt=""></div>
    <?php $url = $this->createUrl(MainConfig::$PAGE_SERVICES, array('id' => $viData['services'][$id]['link'])); ?>
    <?php if( $level != 1 ): ?>
        <div class='header-022 serv'>
          <div>
            <a class='anc' name='<?= $viData['services'][$id]['link'] ?>'></a><a href="<?= $url ?>"><?= $viData['services'][$id]['name'] ?></a>
          </div>
        </div>
    <?php endif; ?>
    <div class='text'>
      <?= $viData['services'][$id]['anons'] ?>
    </div>
    <div class='more btn-white-green-wr' data-id="<?= $id ?>">
      <a href='<?= $url ?>'>Смотреть</a>
    </div>
    <div class="clear"></div>
</div>