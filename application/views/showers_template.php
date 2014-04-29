
<? if ($type === 'edit'): ?>

    <div class="detailsMapWrapper">
        <div class="detailsMap"></div>
    </div>
    <div class="form">
        Open
        <input type="text" name="place[<?= $counter ?>][utilities][showers][from]" placeholder="From" style="width: 35px;" /> -
        <input type="text" name="place[<?= $counter ?>][utilities][showers][to]" placeholder="To" style="width: 35px;" /><br />
        <textarea name="place[<?= $counter ?>][utilities][showers][text]"></textarea><br />
    </div>
    <input class="addPlaceButton" placetype="showers" type="button" value="Save and add another" />

<? elseif ($type === 'nested_edit'): ?>

    <div class="nestedPlace">
        <div class="header">
            <a href="" class="nestedDetailsButton">details</a>
            Shower
        </div>
        <div class="body">
            <div class="detailsMapWrapper">
                <div class="detailsMap"></div>
            </div>
            <div class="form">
            </div>
            <input class="addPlaceButton" placetype="showers" type="button" value="Save and add another" />
        </div>
    </div>

<? endif; ?>
