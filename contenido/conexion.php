<?php
try {
    $conn = new PDO( 'mysql:host=localhost;dbname=db-stpre', 'root', '' );
} catch ( PDOException $e ) {
    print "¡Error!: " . $e->getMessage() . "<br/>";
    die();
}

?>
