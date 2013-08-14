<?php
include('../class.Mostacho.php');

$mostacho = new Mostacho();

$mostacho->addKeyword('foo', 'var');
$mostacho->addKeyword('add', 'addFunc');
$mostacho->addKeyword('nonpresent', 'nonpresent');
$mostacho->addKeyword('present', 'present');

function addFunc() {
	return 3 + 3;
}

//demonstrate that the callback only gets called if the keyword is present
$num = 3;
function nonpresent() {
global $num;
$num = 100;
return $num;
}

function present() {
global $num;
return $num;
}

// mostacho with classes
class Foo {
public function foo_var() {
	return "var";
}
}
$foo_instance = new Foo();

$mostacho->addKeyword('class_foo', array($foo_instance, 'foo_var'));

$mostacho->start();
include('template.html');
$mostacho->flush();
?>
