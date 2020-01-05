<!DOCTTYPE html>
<html>
    <head>
        <title> Video trimmer </title>
    </head>
    <body>
    <form enctype="multipart/form-data" action="validator.php" method="post">
        Select File: <input type="file" name="file">
        <br>
        From second: <input type="number" name="from">
        <br>
        To second: <input type="number" name="to">
        <br>
        <button type="submit"> Convert </button>
    </form>
    </body>
</html>