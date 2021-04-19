--TEST--
Check if mytest is loaded
--SKIPIF--
<?php
if (!extension_loaded('mytest')) {
    echo 'skip';
}
?>
--FILE--
<?php
echo 'The extension "mytest" is available';
?>
--EXPECT--
The extension "mytest" is available
