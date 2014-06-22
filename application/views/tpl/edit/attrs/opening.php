
<style type="text/css">

    #opening h2 { display: inline-block; width: 78px; text-align: right; }
    .tpl-list-row { margin-bottom: 3px; }
    .tpl-details-section { margin: 10px 0; padding: 6px 0 8px; border-top: solid 1px #e0e1e2; background-color: #f5f6f7; }
    .tpl-text-small { width: 30px; }
    .fromtoTimes { margin-left: 10px; display: none; }

</style>

<div class="tpl-section" id="opening" style="width: 400px;">
    <div class="tpl-section-wrapper">

        <div>
            <span id="addOpeningRowButton" style="float: right;">add</span>
            <h1>Opening</h1>
        </div>

        <div class="openingRow">
            
            <h2>For period</h2>

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

            <!-- Which days -->
            <div style="margin-top: 5px;">
                <h2>Days of week</h2>
                <select class="daysOfWeekSelect attr">
                    <option value="na"></option>
                    <option value="sameeachday">Same each day</option>
                    <option value="varies">Different each day</option>
                </select>
            </div>

            <!-- Opening days same -->
            <div class="tpl-details-section openingSame" style="display: none;">
                <h2>Open</h2>
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
                <div id="openingVariesBrief">
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
                <div id="openingVariesFull" style="display: none;">
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

        $('.periodSelect').selectButton({
            select: function(e, ui) {

                if (ui.item.value === 'season') {
                    $('.seasonType').show();
                } else {
                    $('.seasonType').hide();
                }

                if (ui.item.value === 'season' || ui.item.value === 'offseason') {
                    $('.seasonDetails').show();
                } else {
                    $('.seasonDetails').hide();
                }
            }
        });

        $('.daysOfWeekSelect').selectButton({
            select: function(e, ui) {

                if (ui.item.value === 'sameeachday') {
                    $('.openingSame').show();
                } else {
                    $('.openingSame').hide();
                }

                if (ui.item.value === 'varies') {
                    $('.openingVeries').show();
                } else {
                    $('.openingVeries').hide();
                }
            }
        });

        $('.fromtoTimesSelect').selectButton({
            select: function(e, ui) {
                if (ui.item.value === 'fromto') {
                    $(this).closest('div').find('.fromtoTimes').show();
                } else {
                    $(this).closest('div').find('.fromtoTimes').hide();
                }
            }
        });

        $('.toggleDaysButton').click(function() {

            if ($('.openingVariesBrief').is(':visible')) {
                $('.openingVariesBrief').hide();
                $('.openingVariesFull').show();
            } else {
                $('.openingVariesFull').hide();
                $('.openingVariesBrief').show();
            }
        });
        
    });

</script>