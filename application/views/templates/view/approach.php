
<? if (@$attrs->approach->details !== '' || @$attrs->approach->drying->value === 'yes' || @$attrs->approach->drying->details !== NULL): ?>
    <div class="tpl-section">
        <div class="tpl-section-wrapper">

            <h1>Approach</h1>
            <?= @$attrs->approach->details ?>

            <? if (@$attrs->approach->drying->value === 'yes' || @$attrs->approach->drying->details !== ''): ?>
                <div class="tpl-subsection" style="display: block;">
                    <? if (@$attrs->approach->drying->value === 'yes'): ?>
                        Approach can dry.
                    <? endif; ?>
                    <? if (@$attrs->approach->drying->details !== NULL): ?>
                        <div style="font-size: 12px; color: #666;">
                            Details: <?= @$attrs->approach->drying->details ?>
                        </div>
                    <? endif; ?>
                </div>
            <? endif; ?>

        </div>
    </div>
<? endif; ?>