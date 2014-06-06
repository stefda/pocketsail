
<style type="text/css">
    #exposition .tpl-table-item-label { width: 36px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Wind & Swell</h1>

        <table class="tpl-table" id="exposition">
            <tr>
                <td class="tpl-table-item-label">
                    Wind
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" name="attrs[exposure][wind]" value="<?= @$attrs->exposure->wind ?>" />
                </td>
            </tr>
            <tr>
                <td class="tpl-table-item-label">
                    Swell
                </td>
                <td class="tpl-table-item-value">
                    <input class="tpl-text-small attr" name="attrs[exposure][swell]" value="<?= @$attrs->exposure->swell ?>" />
                </td>
            </tr>
        </table>

    </div>
</div>