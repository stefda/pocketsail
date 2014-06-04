
<style type="text/css">
    #berthing .tpl-text-small { width: 30px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Berthing</h1>

        <table id="berthing" class="tpl-table" style="width: 100%;">

            <!-- Assistance -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Assistance
                </td>
                <td class="tpl-table-item-value">
                    <select class="tpl-select-button attr" name="attrs[berthing][assistance][value]">
                        <option value="na">Don't know</option><option value="yes">Yes</option><option value="no">No</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[berthing][assistance][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <!-- Type -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Type
                </td>
                <td class="tpl-table-item-value">
                    <select multiple class="tpl-select-button attr" name="attrs[berthing][type][values][]">
                        <option value="alongiside">Alongside</option><option value="sternto">Stern-to</option>
                        <option value="bowto">Bow-to</option><option value="lazyline">Lazyline</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[berthing][type][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <!-- Sea berths -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Sea berths
                </td>
                <td class="tpl-table-item-value">
                    <input type="text" class="attr tpl-text-small" name="attrs[berthing][berth][total][value]" style="width: 30px;" />
                    /
                    <input type="text" class="attr tpl-text-small" name="attrs[berthing][berth][visitor][value]" style="width: 30px;" />
                    <span class="tpl-note">(total / visitors)</span>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[berthing][berth][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <!-- Dry berths -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Dry berths
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[berthing][dryberth][value]" />
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[berthing][dryberth][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <!-- Max draught -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Max draught
                </td>
                <td class="tpl-table-item-value">
                    <input type="text" class="tpl-text-small attr" name="attrs[berthing][maxdraught][value]" />
                    <select class="tpl-select attr" name="attrs[berthing][maxdraught][type]">
                        <option value="m">meters</option><option value="ft">feet</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[berthing][maxdraught][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <!-- Max length -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Max length
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[berthing][maxlength][value]" />
                    <select class="tpl-select attr" name="attrs[berthing][maxlength][type]">
                        <option value="m">meters</option><option value="ft">feet</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[berthing][maxlength][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <!-- Price -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Price
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[berthing][price][value]" />
                    <select class="tpl-select attr" name="attrs[berthing][price][currency]">
                        <option value="gbp">£</option><option value="eur">€</option><option value="usd">$</option>
                    </select>
                    <select class="tpl-select attr" name="attrs[berthing][price][type]">
                        <option value="m">per meter</option><option value="ft">per foot</option>
                    </select>
                    <span class="tpl-details-button">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[berthing][price][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

            <!-- Soujourn tax -->
            <tr class="tpl-has-details-button">
                <td class="tpl-table-item-label">
                    Soujourn tax
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" type="text" name="attrs[berthing][soujourn][value]" />
                    <select class="tpl-select attr" name="attrs[berthing][soujourn][currency]">
                        <option value="gbp">£</option><option value="eur">€</option><option value="usd">$</option>
                    </select>
                    <select class="tpl-select attr" name="attrs[berthing][soujourn][type]">
                        <option value="person">per person</option><option value="boat">per boat</option>
                    </select>
                    <span class="tpl-details-button" href="">details</span>
                </td>
            </tr>
            <tr class="tpl-details">
                <td colspan="2">
                    <textarea class="tpl-details-small attr" name="attrs[berthing][soujourn][details]" placeholder="Provide any details..."></textarea>
                </td>
            </tr>

        </table>

    </div>
</div>

<script type="text/javascript">

    $(function() {
        validator.add(function() {
            return true;
        });
    });

</script>