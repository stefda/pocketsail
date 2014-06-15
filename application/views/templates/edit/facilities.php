
<style>
    #facilities .tpl-table-item-label { width: 100px; }
    #wifiPrice h2 { margin-left: 10px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Facilities</h1>

        <table class="tpl-table" id="facilities" style="width: 100%;">

            <!-- WATER -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Water
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][water][value]">
                        <option value="na"<?= @$attrs->facilities->water->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->water->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->water->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->water->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->water->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][water][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->water->details ?></textarea>
                </td>
            </tr>

            <!-- ELECTRICITY -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Electricity
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][electricity][value]">
                        <option value="na"<?= @$attrs->facilities->water->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->water->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->water->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->electricity->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->electricity->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][electricity][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->electricity->details ?></textarea>
                </td>
            </tr>

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

            <!-- RECEPTION -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Reception
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][reception][value]">
                        <option value="na"<?= @$attrs->facilities->reception->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->reception->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->reception->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->reception->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->reception->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][reception][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->reception->details ?></textarea>
                </td>
            </tr>

            <!-- CUSTOMS -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Customs
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][customs][value]">
                        <option value="na"<?= @$attrs->facilities->customs->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->customs->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->customs->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->customs->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->customs->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][customs][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->customs->details ?></textarea>
                </td>
            </tr>

            <!-- ENQUIRIES -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Enquiries
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][enquiries][value]">
                        <option value="na"<?= @$attrs->facilities->enquiries->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->enquiries->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->enquiries->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->enquiries->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->enquiries->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][enquiries][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->enquiries->details ?></textarea>
                </td>
            </tr>

            <!-- LAUNDRY -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Laundry
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][laundry][value]">
                        <option value="na"<?= @$attrs->facilities->laundry->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->laundry->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->laundry->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->laundry->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->laundry->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][laundry][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->laundry->details ?></textarea>
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

            <!-- DISABILITY ACCESS -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Disability access
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][disability][value]">
                        <option value="na"<?= @$attrs->facilities->disability->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->disability->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->disability->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->disability->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->disability->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][disability][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->disability->details ?></textarea>
                </td>
            </tr>

            <!-- PETS -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Pets
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][pets][value]">
                        <option value="na"<?= @$attrs->facilities->pets->value === 'na' ? ' selected' : '' ?>>?</option>
                        <option value="yes"<?= @$attrs->facilities->pets->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->pets->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                </td>
                <td>
                    <span class="tpl-details-button<?= @$attrs->facilities->pets->details === null ? '' : ' tpl-visible' ?>">
                        details<span class="tpl-details-button-arrow"></span>
                    </span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="3">
                    <textarea class="tpl-details-small<?= @$attrs->facilities->pets->details === null ? '' : ' attr-include' ?>"
                              name="attrs[facilities][pets][details]"
                              placeholder="Provide any details..."><?= @$attrs->facilities->pets->details ?></textarea>
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

