
<!-- FULLVIEW -->
<? if ($type === 'fullview'): ?>

    <div class="info-block">
        <div class="inner-wrapper">
            <a id="navigation"></a>
            <h1>Navigation</h1>
            <div class="content-wrapper">
                <?= $info['text'] ?>
            </div>
        </div>
    </div>

<? endif; ?>
<!-- /FULLVIEW -->


<!-- EDIT -->
<? if ($type === 'edit'): ?>

    <div class="info-block">
        <div class="inner-wrapper">
            <a id="navigation"></a>
            <h1>Navigation</h1>
            <textarea placeholder="Provide any helpful tips for navigation..."><?= @$info['text'] ?></textarea>
        </div>
    </div>

<? endif; ?>
<!-- /EDIT -->