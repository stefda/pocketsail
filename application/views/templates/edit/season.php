
<style type="text/css">
    input.seasonMonths { font-size: 13px; padding-left: 3px; width: 70px; border: solid 1px #aaa; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>
            Opening Season
        </h1>

        <div class="tpl-has-details-button">

            <select id="season" class="attr" name="attrs[season][value]">
                <option value="na" <?= @$attrs->season->value === 'na' ? 'selected' : '' ?>>Don't know</option>
                <option value="allyear" <?= @$attrs->season->value === 'allyear' ? 'selected' : '' ?>>All year</option>
                <option value="seasonal" <?= @$attrs->season->value === 'seasonal' ? 'selected' : '' ?>>Seasonal</option>
            </select>

            <span id="seasonal" style="margin-left: 10px;<?= @$attrs->season->value === 'seasonal' ? '' : ' display: none;' ?>">
                <select class="attr seasonMonths" name="attrs[season][from]">
                    <option value="">From</option>
                    <? foreach (['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] AS $month): ?>
                        <option value="<?= $month ?>"<?= @$attrs->season->to === $month ? ' selected' : '' ?>><?= date('F', strtotime($month)) ?></option>
                    <? endforeach; ?>
                </select>
                - <select class="attr seasonMonths" name="attrs[season][to]">
                    <option value="">To</option>
                    <? foreach (['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] AS $month): ?>
                        <option value="<?= $month ?>"<?= @$attrs->season->from === $month ? ' selected' : '' ?>><?= date('F', strtotime($month)) ?></option>
                    <? endforeach; ?>
                </select>
            </span>

            <!-- DETAILS BUTTON -->
            <span class="tpl-details-button<?= @$attrs->season->details === null ? '' : ' tpl-visible' ?>">
                details
            </span>

        </div>

        <div class="tpl-details">
            <textarea class="tpl-details-small attr<?= @$attrs->opening->details === null ? '' : ' tpl-details-include' ?>" placeholder="Provide any details..." name="attrs[season][details]"><?= @$attrs->season->details ?></textarea>
        </div>

    </div>
</div>

<script type="text/javascript">

    $(function() {

        $('#season').selectButton({
            select: function(e, ui) {
                if (ui.item.value === 'seasonal') {
                    $('#seasonal').show();
                } else {
                    $('#seasonal').hide();
                }
            }
        });

        $('.seasonMonths').select();
    });

</script>