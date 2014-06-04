
<style>
    #facilities .tpl-table-item-label { width: 100px; }
    #wifiPrice h2 { margin-left: 10px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Facilities</h1>

        <table class="tpl-table" id="facilities" style="width: 100%;">

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Water</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][water][value]">
                        <option value="na"<?= @$attrs->facilities->water->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->water->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->water->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][water][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Electricity</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][electricity][value]">
                        <option value="na"<?= @$attrs->facilities->water->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->water->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->water->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][electricity][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Showers</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][showers][value]">
                        <option value="na"<?= @$attrs->facilities->showers->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->showers->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->showers->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][showers][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Toilets</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][toilets][value]">
                        <option value="na"<?= @$attrs->facilities->toilets->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->toilets->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->toilets->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][toilets][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Waste disposal</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][waste][value]">
                        <option value="na"<?= @$attrs->facilities->waste->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->waste->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->waste->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][waste][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Reception</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][reception][value]">
                        <option value="na"<?= @$attrs->facilities->reception->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->reception->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->reception->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][reception][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Customs</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][customs][value]">
                        <option value="na"<?= @$attrs->facilities->customs->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->customs->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->customs->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][customs][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Enquiries</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][enquiries][value]">
                        <option value="na"<?= @$attrs->facilities->enquiries->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->enquiries->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->enquiries->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][enquiries][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Laundry</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][laundry][value]">
                        <option value="na"<?= @$attrs->facilities->laundry->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->laundry->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->laundry->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][laundry][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>WiFi</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="attr" id="wifi" name="attrs[facilities][wifi][value]">
                        <option value="na"<?= @$attrs->facilities->wifi->value === 'na' ? ' selected' : '' ?>>Don't know</option>
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
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][wifi][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Disability access</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][disability][value]">
                        <option value="na"<?= @$attrs->facilities->disability->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->disability->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->disability->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][disability][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    <h2>Pets</h2>
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[facilities][pets][value]">
                        <option value="na"<?= @$attrs->facilities->pets->value === 'na' ? ' selected' : '' ?>>Don't know</option>
                        <option value="yes"<?= @$attrs->facilities->pets->value === 'yes' ? ' selected' : '' ?>>Yes</option>
                        <option value="no"<?= @$attrs->facilities->pets->value === 'no' ? ' selected' : '' ?>>No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[facilities][pets][details]" placeholder="Provide any details..."></textarea>
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

        $(function() {
            validator.add(function() {
                return true;
            });
        });
    });

</script>

