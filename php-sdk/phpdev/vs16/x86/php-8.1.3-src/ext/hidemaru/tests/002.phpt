--TEST--
test1() Basic test
--SKIPIF--
<?php
if (!extension_loaded('hidemaru')) {
    echo 'skip';
}
?>
--FILE--
<?php
$ret = test1();

var_dump($ret);
?>
--EXPECT--
The extension hidemaru is loaded and working!
NULL
