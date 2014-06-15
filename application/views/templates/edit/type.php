
<style type="text/css">
    #tpl-type .tpl-table-item-label { width: 70px; }
    #tpl-location .tpl-table-item-label { width: 70px; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Type & Location</h1>

        <table class="tpl-table">

            <!-- CATEGORY -->
            <tr>
                <td class="tpl-table-item-label">
                    Category
                </td>
                <td>
                    <select id="catSelectButton" class="tpl-select" name="cat">
                        <? foreach ($cats AS $cat): ?>
                            <option value="<?= $cat->id ?>"<?= $cat->id === $poi->cat ? ' selected' : '' ?>><?= $cat->name ?></option>
                        <? endforeach; ?>
                    </select>
                </td>
            </tr>

            <!-- SUBCATEGORY -->
            <tr>
                <td class="tpl-table-item-label">
                    Subcategory
                </td>
                <td>
                    <select id="subSelectButton" class="tpl-select" name="sub">
                        <? foreach ($subs AS $sub): ?>
                            <option value="<?= $sub->id ?>"<?= $sub->id === $poi->sub ? ' selected' : '' ?>><?= $sub->name ?></option>
                        <? endforeach; ?>
                    </select>
                </td>
            </tr>

        </table>

        <div style="border-top: solid 1px #f0f1f2; margin: 6px 0 11px;"></div>

        <table id="tpl-location" class="tpl-table">

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

        </table>

    </div>
</div>