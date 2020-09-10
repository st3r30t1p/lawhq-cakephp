<!doctype html>
<html lang="en">

<head>
    <?= $this->Html->charset() ?>
    <?= $this->Html->meta('icon') ?>
    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title><?php echo $this->fetch('title') ?> - Law HQ</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />
    <link href="<?php echo $this->Url->build('/themes/bulma/'); ?>bulma.min.css" rel="stylesheet" />

    <?= $this->Html->css('lawhq.0.1.11') ?>
</head>

<body style="position:fixed;top:0;left:0;right:0;bottom:0;background-color:#eaeaea">
    <div style="max-width: 400px; padding: 0 20px; margin: 40vh auto 0; transform: translateY(-50%);">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
    </div>
</body>
</html>
