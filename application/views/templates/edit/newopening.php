
<style type="text/css">
    #openingTable { width: 100%; }
    #everydayTimes .tpl-text-small, #somedaysTimes .tpl-text-small { font-size: 12px; padding-left: 3px; width: 30px; border: solid 1px #aaa; }
    #somedaysTimes .tpl-table-item-label { width: 37px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Open</h1>

        <table class="tpl-table">
            <tr>
                <td class="tpl-table-item-label">
                    Opening for
                </td>
                <td>
                    <select class="tpl-select-button">
                        <option value="allyear">All year</option>
                        <option value="season">Season</option>
                        <option value="out">Off-season</option>
                    </select>
                </td>
            </tr>
        </table>
        
        <table class="tpl-table">
            <tr>
                <td class="tpl-table-item-label">
                    Which season
                </td>
                <td>
                    <select class="tpl-select-button">
                        <option value=""></option>
                        <option value="high">High</option>
                        <option value="mid">Mid</option>
                        <option value="low">Low</option>
                    </select>
                </td>
            </tr>
        </table>
        
        <table class="tpl-table">
            <tr>
                <td class="tpl-table-item-label">
                    Opening
                </td>
                <td>
                    <select class="tpl-select-button">
                        <option value="same">Same each day</option>
                        <option value="different">Different each day</option>
                    </select>
                </td>
            </tr>
        </table>
        
        <table class="tpl-table">
            <tr>
                <td class="tpl-table-item-label">
                    Specify when
                </td>
                <td>
                    <select class="tpl-select-button">
                        <option value=""></option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
                </td>
            </tr>
        </table>
        
        <table class="tpl-table">
            <tr>
                <td class="tpl-table-item-label">
                    <span style="vertical-align: text-top; cursor: pointer;">&#8595;</span> Mon-Fri
                </td>
                <td>
                    <select class="tpl-select-button">
                        <option value=""></option>
                        <option value="Closed">Closed</option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="tpl-table-item-label">
                    Saturday
                </td>
                <td>
                    <select class="tpl-select-button">
                        <option value=""></option>
                        <option value="Closed">Closed</option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="tpl-table-item-label">
                    Sunday
                </td>
                <td>
                    <select class="tpl-select-button">
                        <option value=""></option>
                        <option value="Closed">Closed</option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
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