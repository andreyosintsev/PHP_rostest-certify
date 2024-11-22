<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<?php
    require('wp-load.php');
    $id = $_GET['id'];
    echo $id."\n\r";

    $post = get_post($id);
    var_dump($post);
?>
</body>
</html>