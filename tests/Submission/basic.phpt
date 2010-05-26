--TEST--
Basic announcement submission test
--SKIPIF--
<?php
if (!class_exists('mysqli')) {
    echo 'skip';
}
?>
--POST--
storyid=&_type=story&title=The+Title&description=My+summary+of+the+announcement.&full_article=The+FULL+article+text+goes+here.&request_publish_start=2010-05-27&request_publish_end=2010-05-27&website=&sponsor=Office+of+University+Communications&newsroom_id%5B%5D=1&ajaxupload=true
--FILE--
<?php
require dirname(__FILE__) . '/../setup.inc.php';
echo 'INSERT ID SHOULD BE RETURNED'.PHP_EOL;
$controller = new UNL_ENews_Controller(array('view'=>'submit'));

?>
--EXPECTF--
INSERT ID SHOULD BE RETURNED
%s