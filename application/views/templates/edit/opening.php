
<style type="text/css">
    #openingTable { width: 100%; }
    #everydayTimes .tpl-text-small, #somedaysTimes .tpl-text-small { font-size: 12px; padding-left: 3px; width: 30px; border: solid 1px #aaa; }
    #somedaysTimes .tpl-table-item-label { width: 37px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Open</h1>

        <table id="openingTable" class="tpl-table">
            <tr class="tpl-has-details-button">
                <td>
                    <select id="opening" class="attr" name="attrs[opening][value]">
                        <option value="na"<?= @$attrs->opening->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="everyday"<?= @$attrs->opening->value === 'everyday' ? ' selected' : '' ?>>Every day</option>
                        <option value="somedays"<?= @$attrs->opening->value === 'somedays' ? ' selected' : '' ?>>Some days</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->opening->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->opening->details === null ? '' : ' attr-include' ?>"
                              name="attrs[opening][details]"
                              placeholder="Provide any details..."><?= @$attrs->opening->details ?></textarea>
                </td>
            </tr>
        </table>

        <div class="tpl-subsection" id="everydayDetails" style="<?= @$attrs->opening->value === 'everyday' ? '' : 'display: none;' ?>">

            <h2>Specify times</h2>

            <select id="everyday" class="attr" name="attrs[opening][everyday][value]">
                <option value="na"<?= @$attrs->opening->everyday->value === 'na' ? ' selected' : '' ?>>Not sure</option>
                <option value="24h"<?= @$attrs->opening->everyday->value === '24h' ? ' selected' : '' ?>>24h</option>
                <option value="attimes"<?= @$attrs->opening->everyday->value === 'attimes' ? ' selected' : '' ?>>From-To</option>
            </select>

            <span id="everydayTimes" style="<?= @$attrs->opening->everyday->value === 'attimes' ? '' : 'display: none; ' ?>margin-left: 10px;">
                <input class="tpl-text-small attr" name="attrs[opening][everyday][from]" placeholder="From" value="<?= @$attrs->opening->everyday->from ?>" />
                -
                <input class="tpl-text-small attr" name="attrs[opening][everyday][to]" placeholder="To" value="<?= @$attrs->opening->everyday->to ?>" />
            </span>

        </div>

        <div class="tpl-subsection" id="somedaysDetails" style="<?= @$attrs->opening->value === 'somedays' ? '' : 'display: none;' ?>">

            <table class="tpl-table" id="somedaysTimes">
                <? foreach (['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'] AS $day): ?>
                    <tr>
                        <td class="tpl-table-item-label"><?= ucfirst($day) ?></td>
                        <td class="tpl-table-item-value">
                            <select class="somedays attr" name="attrs[opening][somedays][<?= $day ?>][value]">
                                <option value="na"<?= @$attrs->opening->somedays->{$day}->value === 'na' ? ' selected' : '' ?>>Not sure</option>
                                <option value="closed"<?= @$attrs->opening->somedays->{$day}->value === 'closed' ? ' selected' : '' ?>>Closed</option>
                                <option value="24h"<?= @$attrs->opening->somedays->{$day}->value === '24h' ? ' selected' : '' ?>>24h</option>
                                <option value="attimes"<?= @$attrs->opening->somedays->{$day}->value === 'attimes' ? ' selected' : '' ?>>From-To</option>
                            </select>
                        </td>
                        <td style="padding-left: 10px;<?= @$attrs->opening->somedays->{$day}->value === 'attimes' ? '' : ' display: none;' ?>">
                            <input class="tpl-text-small attr"
                                   name="attrs[opening][somedays][<?= $day ?>][from]"
                                   placeholder="From" value="<?= @$attrs->opening->somedays->{$day}->from ?>" />
                            -
                            <input class="tpl-text-small attr"
                                   name="attrs[opening][somedays][<?= $day ?>][to]"
                                   placeholder="To" value="<?= @$attrs->opening->somedays->{$day}->to ?>" />
                        </td>
                    </tr>
                <? endforeach; ?>
            </table>

        </div>

    </div>
</div>

<script type="text/javascript">

    $(function() {

        $('#opening').selectButton({
            select: function(e, ui) {
                $('#everydayDetails, #somedaysDetails').hide();
                switch (ui.item.value) {
                    case 'everyday':
                        $('#everydayDetails').show();
                        break;
                    case 'somedays':
                        $('#somedaysDetails').show();
                        break;
                }
            }
        });

        $('#everyday').selectButton({
            select: function(e, ui) {
                if (ui.item.value !== 'attimes') {
                    $('#everydayTimes').hide();
                } else {
                    $('#everydayTimes').show();
                }
            }
        });

        $('.somedays').selectButton({
            select: function(e, ui) {
                if (ui.item.value !== 'attimes') {
                    $(this).closest('td').next().hide();
                } else {
                    $(this).closest('td').next().show();
                }
            }
        });

        validator.add(function() {
            return true;
        });
    });

</script>