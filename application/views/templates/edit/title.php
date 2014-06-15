
<style type="text/css">
    #tpl-title { width: 100%; }
    #tpl-title .tpl-table-item-label { width: 50px; }
    #tpl-title input { width: 90%; }
</style>

<div class="tpl-section">
    <div class="tpl-section-wrapper">

        <h1>Name, Label, URL</h1>

        <table id="tpl-title" class="tpl-table">

            <!-- NAME -->
            <tr>
                <td class="tpl-table-item-label">
                    Name
                </td>
                <td>
                    <input class="tpl-text-large" type="text" name="name" value="<?= @$poi->name ?>" />
                </td>
            </tr>

            <!-- LABEL -->
            <tr>
                <td class="tpl-table-item-label">
                    Label
                </td>
                <td>
                    <input class="tpl-text-large" type="text" name="label" value="<?= @$poi->label ?>" />
                </td>
            </tr>

            <!-- URL -->
            <tr>
                <td class="tpl-table-item-label">
                    URL
                </td>
                <td>
                    <input class="tpl-text-large" type="text" name="url" value="<?= @$poi->url ?>" />
                </td>
            </tr>

        </table>

    </div>
</div>