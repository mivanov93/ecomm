
<?php
function getArray() {
    return [1, 2, 3];
}

function squareArray(array &$a) {
    foreach ($a as &$v) {
        $v **= 2;
    }
    return $a;
}

// Generates a warning in PHP 7.
$z=squareArray(array(1,2,3));
var_dump($z);
?>

