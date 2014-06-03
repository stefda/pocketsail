
<div class="par">

    <h1>Berthing</h1>

    <table>
        <tr class="hasDetail">
            <td style="text-align: right; width: 100px;"><h2>Assistance</h2></td>
            <td>
                <select class="attr choose" name="attrs[berthing][assistance][value]"><option value="na">Don't know</option><option value="yes">Yes</option><option value="no">No</option></select>
                <a class="detailsButton" href="">details</a>
            </td>
        </tr>
        <tr style="display: none;">
            <td colspan="2">
                <textarea class="attr smallDetailsText" name="attrs[berthing][assistance][details]" placeholder="Provide any details..."></textarea>
            </td>
        </tr>
        <tr class="hasDetail">
            <td style="text-align: right;"><h2>Type</h2></td>
            <td>
                <select multiple class="attr choose" name="attrs[berthing][type][values][]"><option value="alongiside">Alongside</option><option value="sternto">Stern-to</option><option value="bowto">Bow-to</option><option value="lazyline">Lazyline</option></select>
                <a class="detailsButton" href="">details</a>
            </td>
        </tr>
        <tr style="display: none;">
            <td colspan="2">
                <textarea class="attr smallDetailsText" name="attrs[berthing][type][details]" placeholder="Provide any details..."></textarea>
            </td>
        </tr>
        <tr class="hasDetail">
            <td style="text-align: right;"><h2>Sea berths</h2></td>
            <td>
                <input type="text" class="attr inputSmall" name="attrs[berthing][berth][total][value]" style="width: 30px;" /> / <input type="text" class="attr inputSmall" name="attrs[berthing][berth][visitor][value]" style="width: 30px;" /> <span style="font-size: 11px;">(total / visitors)</span>
                <a class="detailsButton" href="">details</a>
            </td>
        </tr>
        <tr style="display: none;">
            <td colspan="2">
                <textarea class="attr smallDetailsText" name="attrs[berthing][berth][details]" placeholder="Provide any details..."></textarea>
            </td>
        </tr>
        <tr  class="hasDetail">
            <td style="text-align: right;"><h2>Dry berths</h2></td>
            <td>
                <input type="text" class="attr inputSmall" name="attrs[berthing][dryberth][value]" style="width: 30px;" />
                <a class="detailsButton" href="">details</a>
            </td>
        </tr>
        <tr style="display: none;">
            <td colspan="2">
                <textarea class="attr smallDetailsText" name="attrs[berthing][dryberth][details]" placeholder="Provide any details..."></textarea>
            </td>
        </tr>
        <tr class="hasDetail">
            <td style="text-align: right;"><h2>Max draught</h2></td>
            <td>
                <input type="text" class="attr inputSmall" name="attrs[berthing][maxdraught][value]" style="width: 30px;" />
                <select class="attr drop" name="attrs[berthing][maxdraught][type]"><option value="m">meters</option><option value="ft">feet</option></select>
                <a class="detailsButton" href="">details</a>
            </td>
        </tr>
        <tr style="display: none;">
            <td colspan="2">
                <textarea class="attr smallDetailsText" name="attrs[berthing][maxdraught][details]" placeholder="Provide any details..."></textarea>
            </td>
        </tr>
        <tr class="hasDetail">
            <td style="text-align: right;"><h2>Max length</h2></td>
            <td>
                <input type="text" class="attr inputSmall" name="attrs[berthing][maxlength][value]" style="width: 30px;" />
                <select name="attrs[berthing][maxlength][type]" class="attr drop"><option value="m">meters</option><option value="ft">feet</option></select>
                <a class="detailsButton" href="">details</a>
            </td>
        </tr>
        <tr style="display: none;">
            <td colspan="2">
                <textarea class="attr smallDetailsText" name="attrs[berthing][maxlength][details]" placeholder="Provide any details..."></textarea>
            </td>
        </tr>
        <tr class="hasDetail">
            <td style="text-align: right;"><h2>Price</h2></td>
            <td>
                <input type="text" class="attr inputSmall" name="attrs[berthing][price][value]" style="width: 30px;" />
                <select name="attrs[berthing][price][currency]" class="attr drop"><option value="gbp">£</option><option value="eur">€</option><option value="usd">$</option></select>
                <select name="attrs[berthing][price][type]" class="attr drop"><option value="m">per meter</option><option value="ft">per foot</option></select>
                <a class="detailsButton" href="">details</a>
            </td>
        </tr>
        <tr style="display: none;">
            <td colspan="2">
                <textarea class="attr smallDetailsText" name="attrs[berthing][price][details]" placeholder="Provide any details..."></textarea>
            </td>
        </tr>
        <tr class="hasDetail">
            <td style="text-align: right;"><h2>Soujourn tax</h2></td>
            <td>
                <input type="text" class="attr inputSmall" name="attrs[berthing][soujourn][value]" style="width: 30px;" />
                <select name="attrs[berthing][soujourn][currency]" class="attr drop"><option value="gbp">£</option><option value="eur">€</option><option value="usd">$</option></select>
                <select name="attrs[berthing][soujourn][type]" class="attr drop"><option value="person">per person</option><option value="boat">per boat</option></select>
                <a class="detailsButton" href="">details</a>
            </td>
        </tr>
        <tr style="display: none;">
            <td colspan="2">
                <textarea class="attr smallDetailsText" name="attrs[berthing][soujourn][details]" placeholder="Provide any details..."></textarea>
            </td>
        </tr>
    </table>

</div>

<script type="text/javascript">

    $(function() {

        $('.choose').multiButton();
        $('.drop').select();
    });

</script>