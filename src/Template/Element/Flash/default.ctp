<?php
$class = 'message';
if (!empty($params['class'])) {
    $class .= ' ' . $params['class'];
}
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="" onclick="this.classList.add('hidden');"></div>
<div class="row">
  <div class="col s12">
    <div class="card-panel orange lighten-5">
      <span class="<?= h($class) ?>"><?= $message ?></span>
    </div>
  </div>
</div>