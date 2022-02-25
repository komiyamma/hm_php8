--TEST--
Check if hidemaru is loaded
--SKIPIF--
<?php
if (!extension_loaded('hidemaru')) {
    echo 'skip';
}
?>
--FILE--
<?php
echo 'The extension "hidemaru" is available';
?>
--EXPECT--
The extension "hidemaru" is available
