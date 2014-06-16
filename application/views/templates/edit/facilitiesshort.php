
<style>
    #facilities .tpl-table-item-label { width: 100px; }
    #wifiPrice h2 { margin-left: 10px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Facilities</h1>

        <table class="tpl-table" id="facilities" style="width: 100%;">

            <!-- SHOWERS -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Showers
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][showers][value]">
                        <option value="na"<?= @$attrs->facilities->showers->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->showers->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->showers->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->showers->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->showers->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][showers][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->showers->details ?></textarea>
                </td>
            </tr>

            <!-- TOILETS -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Toilets
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][toilets][value]">
                        <option value="na"<?= @$attrs->facilities->toilets->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->toilets->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->toilets->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->toilets->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->toilets->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][toilets][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->toilets->details ?></textarea>
                </td>
            </tr>

            <!-- WASTE DISPOSAL -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Waste disposal
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][waste][value]">
                        <option value="na"<?= @$attrs->facilities->waste->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->waste->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->waste->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->waste->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->waste->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][waste][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->waste->details ?></textarea>
                </td>
            </tr>

            <!-- WIFI -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    WiFi
                </td>
                <td class="tpl-table-item-value">
                    <select class="attr" id="wifi" name="attrs[facilities][wifi][value]">
                        <option value="na"<?= @$attrs->facilities->wifi->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->wifi->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->wifi->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span id="wifiPrice" style="<?= @$attrs->facilities->wifi->value === 'yes' ? '' : 'display: none;' ?>">
                        <h2>Price</h2>
                        <select class="tpl-select attr" name="attrs[facilities][wifi][price][value]">
                            <option value="na"<?= @$attrs->facilities->wifi->price->value === 'na' ? ' selected' : '' ?>>N/A</option>
                            <option value="yes"<?= @$attrs->facilities->wifi->price->value === 'yes' ? ' selected' : '' ?>>Free</option>
                            <option value="no"<?= @$attrs->facilities->wifi->price->value === 'no' ? ' selected' : '' ?>>Paid</option>
                        </select>
                    </span>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->wifi->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->wifi->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][wifi][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->wifi->details ?></textarea>
                </td>
            </tr>

        </table>

    </div>
</div>

<script type="text/javascript">

    $(function() {

        $('#wifi').selectButton({
            select: function(e, ui) {
                if (ui.item.value === 'yes') {
                    $('#wifiPrice').show();
                } else {
                    $('#wifiPrice').hide();
                }
            }
        });
    });

</script>

