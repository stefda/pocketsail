<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>
    <body style="font-family: Arial;">

        <div style="font-size: 16px; margin-bottom: 10px;">
            Found <?= $numFound ?> pois that match query '<?= $term ?>'. Showing first <?= $numDocs ?>
        </div>

        <? if ($spellingError): ?>
            Did you mean "<a href="/test/fulltext?term=<?= $suggestion ?>"><?= $suggestion ?></a>"?
        <? endif; ?>

        <? foreach ($docs AS $doc): ?>
            <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">
                <?= $doc->name ?>
            </div>
            <div style="font-size: 14px; margin-bottom: 20px;">
                <?
                $text = trim($highlights->{$doc->id}->fulltext[0], '.') . '...';
                $text = preg_replace('/^[^\p{L}<]+/', '', $text);
                if (!ctype_upper(substr($text, 0, 1))) {
                    $text = '...' . $text;
                }
                echo $text;
                ?>
            </div>
        <? endforeach; ?>

    </body>
</html>