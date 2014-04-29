<html>
<head>
<title>404 Page Not Found</title>
<style type="text/css">
body {
    background-color: #eee;
    font-family: Verdana, Sans-serif;
    font-size: 12px;
    color: #000;
    text-align: center;
}

#content  {
    border: solid 2px #aaa;
    background-color: #fff;
    width: 800px;
    margin: 40px auto 0 auto;
    text-align: left;
    padding: 30px;
}

h1 {
    font-weight: bold;
    font-size: 16px;
    margin: 0 0 10px 0;
}
</style>
</head>
<body>
    <div id="content">
        <h1>Oops, <?php echo $heading; ?>!</h1>
        <?php echo $message; ?>
    </div>
</body>
</html>