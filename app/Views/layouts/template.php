<?= $this->insert('partials/head', ['theme' => $theme ?? '']) ?>

<?= $this->section('slideshow') ?>

<?= $this->section('mainSection'); ?>

<?= $this->section('footer'); ?>

<?php if (checkAuth([1])): ?>
<?= $this->insert('./admin/includes/sidebar') ?>
<?= $this->insert("admin/includes/buttons") ?>
<?php endif; ?>

<script type="module" src="<?= assetsUrl('js/bootstrap.js') ?>" type="module"></script>

<?php if (checkAuth([1])): ?>
<script src="<?= assetsUrl("tinyeditor/plugins/jqueryui/js/jquery-3.6.0.min.js") ?>"></script>
<script src="<?= assetsUrl("tinyeditor/plugins/jqueryui/js/jquery-ui.js") ?>"></script>
<script src="<?= assetsUrl("tinyeditor/tinymce.min.js") ?>"></script>
<script src="<?= assetsUrl("tinyeditor/filemanager/js/elfinder.min.js") ?>"></script>
<script src="<?= assetsUrl("filemanager/js/extras/editors.default.min.js") ?>"></script>
<script src="<?= assetsUrl("tinyeditor/filemanagerModal.js") ?>"></script>
<?php endif; ?>

</body>
</html>