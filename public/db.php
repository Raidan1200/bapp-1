<pre>
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $db = new PDO(
        'mysql:host=localhost;dbname=order_66',
        'usr_DB66_order',
        'w79q#S6r',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    var_dump($db);

    $query = $db->query("SHOW FULL TABLES WHERE table_type = 'BASE TABLE'");
    var_dump($query->fetchAll(PDO::FETCH_COLUMN));
} catch(PDOException $ex){
    var_dump($ex);
}

phpinfo();
?>
</pre>