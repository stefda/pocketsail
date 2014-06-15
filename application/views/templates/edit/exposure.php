
<style type="text/css">
    #exposition .tpl-table-item-label { width: 43px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Wind & Swell</h1>

        <table class="tpl-table" id="exposition">

            <!-- WIND -->
            <tr>
                <td class="tpl-table-item-label">
                    Wind
                </td>
                <td>
                    <input class="tpl-text-large attr" name="attrs[exposure][wind]" value="<?= @$attrs->exposure->wind ?>" />
                </td>
            </tr>

            <!-- SWELL -->
            <tr>
                <td class="tpl-table-item-label">
                    Swell
                </td>
                <td>
                    <input class="tpl-text-large attr" name="attrs[exposure][swell]" value="<?= @$attrs->exposure->swell ?>" />
                </td>
            </tr>

        </table>

    </div>
</div>