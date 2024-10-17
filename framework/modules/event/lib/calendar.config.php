<?
// first, we get the timestamp for the first day of the month provided
// so we can use it with our date() function
$month = date("m");
$year  = date("Y");
$first = mktime(0,0,0,$month,1,$year);

// $offset defines how many empty calendar days we need to display before
// the first of the month. this is equivalent to the day of the week on 
// which the first falls
$offset = date('w', $first);

// determine how many days in the month, so we can draw our grid
$daysInMonth = date('t', $first);

// grab the textual name of the month for displaying in the header
$monthName = date('F', $first);

// declare visual days of the week to show as column headers
$weekDays = array('Su','M','Tu','W','Th','F','Sa');

// get an array of all days this month that have events

?>