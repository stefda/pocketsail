
<div>

    <div class="tpl-column-right">

        <?= isset($cats) ? include_view('templates/edit/type') : ''; ?>

        <div class="replace">
            <?= include_view('templates/edit/contact'); ?>
            <?= include_view('templates/edit/charts'); ?>
            <?= include_view('templates/edit/sources'); ?>
        </div>
    </div>

    <div class="tpl-column-left">

        <?= isset($poi) ? include_view('templates/edit/title') : ''; ?>

        <div class="replace">
            <?= include_view('templates/edit/description'); ?>
            <?= include_view('templates/edit/exposure'); ?>
        </div>
    </div>

</div>