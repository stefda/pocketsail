<!DOCTYPE html>
<html>
    <head>
        <title>404 Page Not Found</title>
        <style type="text/css">
            body {
                background-color: #eee;
                font-family: Calibri, Tahoma, Sans-serif;
                font-size: 16px;
                color: #000;
                text-align: center;
            }

            .content  {
                border: solid 1px #d0d1d2;
                border-radius: 4px;
                background-color: #fff;
                width: 800px;
                margin: 40px auto 0 auto;
                text-align: left;
                padding: 30px;
            }

            .details {
                margin-top: 20px;
                font-size: 14px;
            }

            .detailsWrapper {
                margin-top: 10px;
                padding: 10px;
                background-color: #f7f8f9;
            }

            .detailsTable {
                border-collapse: collapse;
            }

            .key {
                font-style: italic;
                text-align: right;
                vertical-align: top;
                padding-right: 10px;
            }

            h1 {
                font-weight: bold;
                font-size: 20px;
                margin: 0 0 10px 0;
            }
        </style>
    </head>
    <body>
        <div class="content">
            <h1>
            <? if ($type === "error"): ?>
                Oops, an error occured!
            <? elseif ($type === "uncaught_exception"): ?>
                Jejda, you haven't caught an exception!
            <? endif; ?>
            </h1>
            <?= $message ?>
            <div class="details">
                <? foreach ($details AS $record): ?>
                    <div class="detailsWrapper">
                        <table>
                            <? foreach ($record AS $key => $value): ?>
                                <tr>
                                    <? if (in_array($key, ['file', 'line', 'function', 'class'])): ?>
                                        <td class="key"><?= $key ?></td><td><?= $value ?></td>
                                        <? endif; ?>
                                </tr>
                            <? endforeach; ?>
                        </table>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
    </body>
</html>