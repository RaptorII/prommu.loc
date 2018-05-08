<?php if( $viErrorData['err'] ): ?>
  <div class="err-msg-block">При сохранении данных профиля произошла ошибка. <?= $viErrorData['msg'] ?></div>
<?php endif; ?>
<?php if( $mess = Yii::app()->user->getFlash('Message') ): Yii::app()->user->setFlash('Message', '') ?>
    <div class="comm-mess-box <?= $mess['type'] == 'error' ? 'red' : 'green' ?>"><?= $mess['message'] ?></div>
<?php endif; ?>

<div class='row page-applicant-profile-edit'>
  <?php
    if( Yii::app()->getRequest()->getParam('ep', 0) == 1 ) $this->renderPartial('page-edit-profile-employer' . '/page-edit-photo-tpl', array('viData' => $viData));
    else $this->renderPartial('page-edit-profile-employer' . '/page-edit-profile-employer-tpl', array('viData' => $viData));
  ?>
</div>
