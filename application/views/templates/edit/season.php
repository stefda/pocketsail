
<style type="text/css">
    #seasonTable { width: 100%; }
    input.seasonMonths { font-size: 13px; padding-left: 3px; width: 70px; border: solid 1px #aaa; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>
            Opening Season
        </h1>

        <table id="seasonTable" class="tpl-table">
            <tr class="tpl-has-details-button">
                <td>
                    <select id="season" class="attr" name="attrs[season][value]">
                        <option value="na" <?= @$attrs->season->value === 'na' ? 'selected' : '' ?>>?</option>
                        <option value="allyear" <?= @$attrs->season->value === 'allyear' ? 'selected' : '' ?>>All year</option>
                        <option value="seasonal" <?= @$attrs->season->value === 'seasonal' ? 'selected' : '' ?>>Seasonal</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->season->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->season->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][water][details]"
                              placeholder="Provide any details..."><?= @$attrs->season->details ?></textarea>
                </td>
            </tr>
        </table>

        <div class="tpl-subsection" id="seasonal" style="<?= @$attrs->season->value === 'seasonal' ? '' : ' display: none;' ?>">
            <h2>
                Specify months
            </h2>
            <select class="attr seasonMonths" name="attrs[season][from]">
                <option value="">From</option>
                <? foreach (['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] AS $month): ?>
                    <option value="<?= $month ?>"<?= @$attrs->season->from === $month ? ' selected' : '' ?>>
                        <?= date('F', strtotime($month)) ?>
                    </option>
                <? endforeach; ?>
            </select>
            -
            <select class="attr seasonMonths" name="attrs[season][to]">
                <option value="">To</option>
                <? foreach (['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] AS $month): ?>
                    <option value="<?= $month ?>"<?= @$attrs->season->to === $month ? ' selected' : '' ?>>
                        <?= date('F', strtotime($month)) ?>
                    </option>
                <? endforeach; ?>
            </select>
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