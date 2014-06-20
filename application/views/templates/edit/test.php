
<? global $attr; ?>

<div>
    Desc: <?= a() ?>
</div>

<? if (a('details')): ?>
    <div>
        Details: <?= a('details'); ?>
    </div>
<? endif; ?>