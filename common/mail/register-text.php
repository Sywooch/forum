<?php
$resetLink = \Yii::$app->urlManager->createAbsoluteUrl(['active/active', 'token' => $user]);
?>
<h1>欢迎您来到BBS社区</h1>
<p>请点击按钮激活您的邮箱！</p>
<a href="<?= $resetLink ?>">去激活</a>