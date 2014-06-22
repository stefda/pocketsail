
<style type="text/css">

    #opening h2 { display: inline-block; width: 78px; text-align: right; }
    .tpl-list-row { margin-bottom: 3px; }
    .tpl-details-section { margin: 5px 0 10px; padding: 6px 0 8px; border-top: solid 1px #e0e1e2; background-color: #f5f6f7; }

</style>

<div class="tpl-section" id="opening" style="width: 400px;">
    <div class="tpl-section-wrapper">

        <h1>Opening</h1>

        <h2>For period</h2>

        <select class="attr" id="forSelect">
            <option value="na"></option>
            <option value="allyear">All year</option>
            <option value="season">Season</option>
            <option value="offseason">Off-season</option>
        </select>

        <!-- Months -->
        <div id="months" style="display: none; margin: 5px 0 10px; padding: 6px 0 8px; border-top: solid 1px #e0e1e2; background-color: #f5f6f7;">

            <div class="tpl-list-row" id="seasonType" style="display: none;">
                <h2>Type</h2>
                <select class="tpl-select-button attr" id="seasonTypeSelect">
                    <option value="na"></option>
                    <option value="high">High</option>
                    <option value="mid">Mid</option>
                    <option value="low">Low</option>
                </select>
            </div>

            <h2>Months</h2>

            <select class="tpl-select attr">
                <option value="">From</option>
                <? foreach (['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] AS $month): ?>
                    <option value="<?= $month ?>">
                        <?= date('F', strtotime($month)) ?>
                    </option>
                <? endforeach; ?>
            </select>
            -
            <select class="tpl-select attr">
                <option value="">To</option>
                <? foreach (['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'] AS $month): ?>
                    <option value="<?= $month ?>">
                        <?= date('F', strtotime($month)) ?>
                    </option>
                <? endforeach; ?>
            </select>

        </div>

        <!-- Which days? -->
        <div style="margin-top: 5px;">
            <h2>Days of week</h2>
            <select class="attr" id="daysOfWeekSelect">
                <option value="na"></option>
                <option value="sameeachday">Same each day</option>
                <option value="varies">Different each day</option>
            </select>
        </div>

        <!-- Opening same -->
        <div class="tpl-details-section" id="openingSame" style="display: none;">
            <h2>Open</h2>
            <select class="tpl-select-button attr" id="seasonTypeSelect">
                <option value="na"></option>
                <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                <option value="fromto">From-To</option>
            </select>
        </div>

        <!-- Opening varies -->
        <div class="tpl-details-section" id="openingVeries" style="display: none;">

            <div id="openingVariesBrief">
                <div class="tpl-list-row">
                    <h2>Mon-Fri</h2>
                    <select class="tpl-select-button attr" id="seasonTypeSelect">
                        <option value="na"></option>
                        <option value="closed">Closed</option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
                </div>
                <div class="tpl-list-row">
                    <h2>Saturday</h2>
                    <select class="tpl-select-button attr" id="seasonTypeSelect">
                        <option value="na"></option>
                        <option value="closed">Closed</option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
                </div>
                <div class="tpl-list-row">
                    <h2>Sunday</h2>
                    <select class="tpl-select-button attr" id="seasonTypeSelect">
                        <option value="na"></option>
                        <option value="closed">Closed</option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
                </div>
                <div>
                    <h2>Holidays</h2>
                    <select class="tpl-select-button attr" id="seasonTypeSelect">
                        <option value="na"></option>
                        <option value="closed">Closed</option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
                </div>
            </div>

        </div>

    </div>
</div>

<script type="text/javascript">

    $(function() {

        $('#forSelect').selectButton({
            select: function(e, ui) {

                if (ui.item.value === 'season') {
                    $('#seasonType').show();
                } else {
                    $('#seasonType').hide();
                }

                if (ui.item.value === 'season' || ui.item.value === 'offseason') {
                    $('#months').show();
                } else {
                    $('#months').hide();
                }
            }
        });

        $('#daysOfWeekSelect').selectButton({
            select: function(e, ui) {

                if (ui.item.value === 'sameeachday') {
                    $('#openingSame').show();
                } else {
                    $('#openingSame').hide();
                }

                if (ui.item.value === 'varies') {
                    $('#openingVeries').show();
                } else {
                    $('#openingVeries').hide();
                }
            }
        });
    });

</script>