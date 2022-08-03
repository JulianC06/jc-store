<?php
try {
    $conn = new PDO( 'mysql:host=db-jc-store.ctusat0p321u.us-east-1.rds.amazonaws.com;dbname=db_jc_store', 'admin', 'password' );
} catch ( PDOException $e ) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>
