<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
<?php
    require('wp-load.php');
    $companies = array();
    $companies = getAllCompanies();

    echo '<ol>';
    foreach($companies as $name=>$count) {
    	echo '<li>'.$name.' ('.$count.')</li>';
    }
    echo '</ol>';
?>
</body>
</html>