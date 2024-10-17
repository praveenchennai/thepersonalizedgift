<?php

include_once("class.SDD.php") ;

class a
{
  var $a = 1 ;
}

class b extends a
{
  var $b = 2 ;
}

class c extends b
{
  var $c = 3 ;
}

$theClass = new c() ;
$theArray = array('a' => 1, 0 => 2, 'b' => 3) ;

echo SDD::dump($theClass) ;
echo SDD::dump($theArray) ;
?>