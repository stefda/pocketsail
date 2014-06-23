
<style type="text/css">

    #opening h2 { display: inline-block; width: 70px; text-align: right; }
    .tpl-list-row { margin-bottom: 3px; }
    .tpl-details-section { margin: 10px 0; padding: 6px 0 8px; border-top: solid 1px #e0e1e2; background-color: #f4f5f6; }
    .tpl-text-small { width: 30px; }
    .fromtoTimes { margin-left: 10px; display: none; }

</style>

<div class="tpl-section" id="opening" style="width: 400px;">
    <div class="tpl-section-wrapper">

        <div>
            <input type="button" id="addOpeningRowButton" style="float: right; border: solid 1px #e0e1e2; background-color: #fff; color: #666; padding: 3px 5px; font-size: 11px; border-radius: 2px; box-shadow: 0 1px 0px rgba(0, 0, 0, 0.1); cursor: pointer;" value="Add opening period">
            <h1>Opening</h1>
        </div>

        <div class="openingRow">

            <h2>Period</h2>

            <select class="periodSelect attr">
                <option value="na"></option>
                <option value="allyear">All year</option>
                <option value="season">Season</option>
                <option value="offseason">Off-season</option>
            </select>

            <!-- Season details -->
            <div class="tpl-details-section seasonDetails" style="display: none;">

                <div class="tpl-list-row seasonType attr" style="display: none;">
                    <h2>Type</h2>
                    <select class="tpl-select-button attr">
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

            <!-- Opening -->
            <div style="margin-top: 5px;">
                <h2>Opening</h2>
                <select class="daysOfWeekSelect attr">
                    <option value="na"></option>
                    <option value="closed">Closed</option>
                    <option value="sameeachday">Open daily</option>
                    <option value="varies">Varies</option>
                </select>
            </div>

            <!-- Opening days same -->
            <div class="tpl-details-section openingSame" style="display: none;">
                <h2>Each day</h2>
                <select class="fromtoTimesSelect attr">
                    <option value="na"></option>
                    <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                    <option value="fromto">From-To</option>
                </select>
                <span class="fromtoTimes">
                    <input type="text" class="tpl-text-small" placeholder="From" />
                    -
                    <input type="text" class="tpl-text-small" placeholder="To" />
                </span>
            </div>

            <!-- Opening days vary -->
            <div class="tpl-details-section openingVeries" style="display: none; position: relative;">

                <!-- Brief opening times -->
                <div class="openingVariesBrief">
                    <span class="toggleDaysButton" style="cursor: pointer; position: absolute; left: 10px;">&#8595;</span>
                    <div class="tpl-list-row">
                        <h2>Mon-Fri</h2>
                        <select class="fromtoTimesSelect attr">
                            <option value="na"></option>
                            <option value="closed">Closed</option>
                            <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                            <option value="fromto">From-To</option>
                        </select>
                        <span class="fromtoTimes">
                            <input type="text" class="tpl-text-small" placeholder="From" />
                            -
                            <input type="text" class="tpl-text-small" placeholder="To" />
                        </span>
                    </div>
                </div>

                <!-- Detailed opening times -->
                <div class="openingVariesFull" style="display: none;">
                    <span class="toggleDaysButton" style="cursor: pointer; position: absolute; left: 10px;">&#8593;</span>
                    <div class="tpl-list-row">
                        <h2>Monday</h2>
                        <select class="fromtoTimesSelect attr">
                            <option value="na"></option>
                            <option value="closed">Closed</option>
                            <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                            <option value="fromto">From-To</option>
                        </select>
                        <span class="fromtoTimes">
                            <input type="text" class="tpl-text-small" placeholder="From" />
                            -
                            <input type="text" class="tpl-text-small" placeholder="To" />
                        </span>
                    </div>
                    <? foreach (['tue', 'wed', 'thu', 'fri'] AS $day): ?>
                        <div class="tpl-list-row">
                            <h2><?= date('l', strtotime($day)) ?></h2>
                            <select class="fromtoTimesSelect attr">
                                <option value="na"></option>
                                <option value="closed">Closed</option>
                                <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                                <option value="fromto">From-To</option>
                            </select>
                            <span class="fromtoTimes">
                                <input type="text" class="tpl-text-small" placeholder="From" />
                                -
                                <input type="text" class="tpl-text-small" placeholder="To" />
                            </span>
                        </div>
                    <? endforeach; ?>
                </div>

                <div class="tpl-list-row">
                    <h2>Saturday</h2>
                    <select class="fromtoTimesSelect attr">
                        <option value="na"></option>
                        <option value="closed">Closed</option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
                    <span class="fromtoTimes">
                        <input type="text" class="tpl-text-small" placeholder="From" />
                        -
                        <input type="text" class="tpl-text-small" placeholder="To" />
                    </span>
                </div>
                <div class="tpl-list-row">
                    <h2>Sunday</h2>
                    <select class="fromtoTimesSelect attr">
                        <option value="na"></option>
                        <option value="closed">Closed</option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
                    <span class="fromtoTimes">
                        <input type="text" class="tpl-text-small" placeholder="From" />
                        -
                        <input type="text" class="tpl-text-small" placeholder="To" />
                    </span>
                </div>
                <div>
                    <h2 style="font-style: italic;">Holidays</h2>
                    <select class="fromtoTimesSelect attr">
                        <option value="na"></option>
                        <option value="closed">Closed</option>
                        <option value="24h">&nbsp;&nbsp;24h&nbsp;&nbsp;</option>
                        <option value="fromto">From-To</option>
                    </select>
                    <span class="fromtoTimes">
                        <input type="text" class="tpl-text-small" placeholder="From" />
                        -
                        <input type="text" class="tpl-text-small" placeholder="To" />
                    </span>
                </div>

            </div>
        </div>

    </div>
</div>

<script type="text/javascript">

    $(function() {

        function initUI(wrapper) {

            // Show season options
            wrapper.find('.periodSelect').selectButton({
                select: function(e, ui) {

                    var wrapper = $(this).closest('.openingRow');

                    if (ui.item.value === 'season') {
                        wrapper.find('.seasonType').show();
                    } else {
                        wrapper.find('.seasonType').hide();
                    }

                    if (ui.item.value === 'season' || ui.item.value === 'offseason') {
                        wrapper.find('.seasonDetails').show();
                    } else {
                        wrapper.find('.seasonDetails').hide();
                    }
                }
            });

            wrapper.find('.daysOfWeekSelect').selectButton({
                select: function(e, ui) {

                    var wrapper = $(this).closest('.openingRow');

                    if (ui.item.value === 'sameeachday') {
                        wrapper.find('.openingSame').show();
                    } else {
                        wrapper.find('.openingSame').hide();
                    }

                    if (ui.item.value === 'varies') {
                        wrapper.find('.openingVeries').show();
                    } else {
                        wrapper.find('.openingVeries').hide();
                    }
                }
            });

            wrapper.find('.fromtoTimesSelect').selectButton({
                select: function(e, ui) {
                    if (ui.item.value === 'fromto') {
                        $(this).closest('div').find('.fromtoTimes').show();
                    } else {
                        $(this).closest('div').find('.fromtoTimes').hide();
                    }
                }
            });

            wrapper.find('.toggleDaysButton').click(function() {

                var wrapper = $(this).closest('.openingRow');

                if (wrapper.find('.openingVariesBrief').is(':visible')) {
                    wrapper.find('.openingVariesBrief').hide();
                    wrapper.find('.openingVariesFull').show();
                } else {
                    wrapper.find('.openingVariesFull').hide();
                    wrapper.find('.openingVariesBrief').show();
                }
            });

            wrapper.find('.tpl-select').select();
            wrapper.find('.tpl-select-button').selectButton();
        }

        initUI($('.openingRow'));

        $('#addOpeningRowButton').click(function() {

            var openingRow = $('.openingRow:last');
            var newOpeningRow = openingRow.clone();

            newOpeningRow.css('margin', '10px -5px -5px -5px');
            newOpeningRow.css('padding', '10px');
            newOpeningRow.css('background-color', '#fafbfc');

            openingRow.after(newOpeningRow);
            initUI(newOpeningRow);
        });

    });

</script>