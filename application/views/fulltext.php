<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body style="font-family: Arial;">
        
        <div style="font-size: 16px; margin-bottom: 10px;">
            Found <?= $numFound ?> pois that match query '<?= $term ?>'. Showing first <?= $numDocs ?>
        </div>

        <? foreach ($docs AS $doc): ?>
            <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">
                <?= $doc->name ?>
            </div>
            <div style="font-size: 10px; margin-bottom: 20px;">
                <?= $doc->fulltext ?>
            </div>
        <? endforeach; ?>

    </body>
</html>