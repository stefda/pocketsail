
<!-- FULLVIEW -->
<? if ($type === 'fullview'): ?>

    <div class="info-block">
        <div class="inner-wrapper">
            <div class="content-wrapper">
                <a id="description"></a>
                <?= @$info['text'] ?>
            </div>
        </div>
    </div>

<? endif; ?>
<!-- /FULLVIEW -->


<!-- EDIT -->
<? if ($type === 'edit'): ?>

    <div class="info-block">
        <div class="inner-wrapper">
            <textarea placeholder="Describe this place..."><?= @$info['text'] ?></textarea>
        </div>
    </div>

<? endif; ?>
<!-- /EDIT -->
