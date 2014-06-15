
<style type="text/css">
    #tpl-location .tpl-table-item-label { width: 70px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Location</h1>

        <table id="tpl-location" class="tpl-table">

            <!-- NEAR -->
            <tr>
                <td class="tpl-table-item-label">
                    Near
                </td>
                <td>
                    <select class="tpl-select" name="cat">
                        <? foreach ($nears AS $near): ?>
                            <option value="<?= $near->id() ?>"><?= $near->name() ?></option>
                        <? endforeach; ?>
                    </select>
                </td>
            </tr>

            <!-- COUNTRY -->
            <tr>
                <td class="tpl-table-item-label">
                    Country
                </td>
                <td>
                    <select id="" class="tpl-select" name="cat">
                        <? foreach ($countries AS $country): ?>
                            <option value="<?= $country->id() ?>"><?= $country->name() ?></option>
                        <? endforeach; ?>
                    </select>
                </td>
            </tr>

        </table>

    </div>
</div>