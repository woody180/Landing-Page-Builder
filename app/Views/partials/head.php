<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="baseurl" content="<?= baseUrl() ?>">
    <meta name="checkauth" content="<?= checkAuth([1]) ? 'true' : 'false' ?>">
    <meta name="description" content="<?= initModel('settings')->getSettings('description') ?>">
    <meta name="keywords" content="<?= initModel('settings')->getSettings('keywords') ?>">
    
    <script src="<?= assetsUrl('js/uikit.min.js') ?>"></script>
    <script src="<?= assetsUrl('js/uikit-icons.min.js') ?>"></script>
    
    <link rel="icon" type="image/png" href="<?= assetsUrl("tinyeditor/filemanager/files/") . initModel('settings')->getSettings("favicon") ?>" />

    <link rel="stylesheet" href="<?= assetsUrl('css/uikit.min.css') ?>">
    <link rel="stylesheet" href="<?= assetsUrl('css/main.min.css') ?>">
    
    <?php if (checkAuth([1])): ?>
        <script src="<?= assetsUrl("js/coloris.js") ?>"></script>
        <script src="<?= assetsUrl("tinyeditor/tinyeditor.js") ?>"></script>
        <link rel="stylesheet" href="<?= assetsUrl("css/coloris.css") ?>"/>

        <!-- Admin dependencies -->
        <link rel="stylesheet" href="<?= assetsUrl("tinyeditor/plugins/jqueryui/css/jquery-ui.css") ?>">
        <link rel="stylesheet" href="<?= assetsUrl("tinyeditor/filemanager/filemanager.css") ?>">
        <link rel="stylesheet" href="<?= assetsUrl("tinyeditor/filemanager/css/elfinder.min.css") ?>">
        <link rel="stylesheet" href="<?= assetsUrl("tinyeditor/filemanager/css/theme.css") ?>">
    <?php endif; ?>
    
    <title><?= initModel('settings')->getSettings("title") ?? APPNAME; ?></title>
</head>
<body>

    <?php $this->insert('partials/header', ['theme' => $theme]) ?>