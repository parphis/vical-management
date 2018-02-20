<?php
require_once 'Category.php';
require_once 'Event.php';

function log_msg($msg) {
  $d = date("Y-m-dTH:i:s");
  $f = __FILE__;
  $l = "ERR";
  error_log("\n[{$d}] [{$f}] [{$l}] $msg",3,"vicalmgmtlog");
  return "<p class='error'>Valami hiba történt</p><img class='error' src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA2NCA2NCI+PGRlZnM+PGxpbmVhckdyYWRpZW50IHkyPSIxNjEuMjkiIHgyPSIwIiB5MT0iMjE4LjIyIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgaWQ9IjAiPjxzdG9wIHN0b3AtY29sb3I9IiNjNTI4MjgiLz48c3RvcCBvZmZzZXQ9IjEiIHN0b3AtY29sb3I9IiNmZjU0NTQiLz48L2xpbmVhckdyYWRpZW50PjwvZGVmcz48ZyB0cmFuc2Zvcm09Im1hdHJpeCguOTI4NTcgMCAwIC45Mjg1Ny02NjYuOTQtMTQ0LjM3KSI+PGNpcmNsZSByPSIyOCIgY3k9IjE4OS45MyIgY3g9Ijc1Mi43IiBmaWxsPSJ1cmwoIzApIi8+PGcgZmlsbD0iI2ZmZiIgZmlsbC1vcGFjaXR5PSIuODUxIj48cGF0aCBkPSJtNzM5LjU0IDE4MC4yM2MwLTIuMTY2IDEuNzU2LTMuOTIyIDMuOTIyLTMuOTIyIDIuMTY1IDAgMy45MjIgMS43NTYgMy45MjIgMy45MjIgMCAyLjE2Ny0xLjc1NiAzLjkyMy0zLjkyMiAzLjkyMy0yLjE2NiAwLTMuOTIyLTEuNzU2LTMuOTIyLTMuOTIzbTE3Ljc4NCAwYzAtMi4xNjYgMS43NTgtMy45MjIgMy45MjMtMy45MjIgMi4xNjUgMCAzLjkyMiAxLjc1NiAzLjkyMiAzLjkyMiAwIDIuMTY3LTEuNzU2IDMuOTIzLTMuOTIyIDMuOTIzLTIuMTY2IDAtMy45MjMtMS43NTYtMy45MjMtMy45MjMiLz48cGF0aCBkPSJtNzY2Ljg5IDIwMC41MWMtMi40MzEtNS42MjEtOC4xMjMtOS4yNTMtMTQuNTAyLTkuMjUzLTYuNTE2IDAtMTIuMjQyIDMuNjUtMTQuNTg4IDkuMy0uNDAyLjk2Ny4wNTYgMi4wNzggMS4wMjUgMi40OC4yMzguMDk3LjQ4NS4xNDQuNzI3LjE0NC43NDQgMCAxLjQ1LS40NCAxLjc1My0xLjE3IDEuNzU2LTQuMjI5IDYuMTA3LTYuOTYgMTEuMDgtNi45NiA0Ljg2NCAwIDkuMTg5IDIuNzMzIDExLjAyIDYuOTY1LjQxNi45NjIgMS41MzMgMS40MDUgMi40OTUuOTg5Ljk2MS0uNDE3IDEuNDA1LTEuNTMzLjk4OS0yLjQ5NSIvPjwvZz48L2c+PC9zdmc+' />";
}

$class = $_GET['calendarClass'];
$func = $_GET['_call'];
$filter = $_GET['filter'];

if ($filter=='*') {
  $filter = null;
}

if (empty($class) || $class=='' || $class===FALSE) {
  return;
}

if (empty($func) || $func=='' || $func===FALSE) {
  return;
}

try {
  $obj = new $class();
  $r = $obj->$func($filter);
  echo $r;
}
catch (Exception $e) {
  echo log_msg($e);
}
?>
