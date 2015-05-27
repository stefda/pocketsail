<!DOCTYPE html>
<html>

    <head>
        <title>Pocketsail - fulltext search</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link rel="icon" type="image/png"  href="/application/images/favicon4.png">
        <link type="text/css" rel="stylesheet" href="/application/layout/global.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/map.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/template.css" />
        <link type="text/css" rel="stylesheet" href="/application/layout/ui.css" />
        <style>
            a:visited { color: #2a6cda; }
        </style>
    </head>

    <body style="font-family: Arial;">

        <div class="wrapper">

            <div id="header">

                <div style="margin: 12px 0 0 20px;">
                    <img src="/application/images/logo.png"/>
                </div>

            </div>

            <div style="padding: 80px 0 0 20px;">

                <div style="font-size: 16px; margin-bottom: 20px; color: #888;">
                    Showing <?= $numDocs ?> of <?= $numFound ?> results matching query <i>"<?= $term ?>"</i>
                </div>

                <? if ($spellingError): ?>
                    Did you mean "<a href="/test/fulltext?term=<?= $suggestion ?>"><?= $suggestion ?></a>"?
                <? endif; ?>

                <? foreach ($docs AS $doc): ?>
                    <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">
                        <a href="http://<?= DOMAIN ?>/#<?= $doc->id ?>"><?= $doc->name ?> (<?= $doc->subName ?>)</a>
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

            </div>

            <div id="footer" style="margin-top: 100px;">
                <div class="footer-content">
                    Pocketsail &copy; 2015, with <img src="/application/images/love.png" style="vertical-align: bottom;"/> from London.
                </div>
            </div>

        </div>

    </body>
</html>