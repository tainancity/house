<table class="table table-bordered">
<?php
$headers = array_keys($report[key($report)]);
echo '<thead><tr>';
foreach($headers AS $header) {
    echo '<th>' . $header . '</th>';
}
echo '</tr></thead><tbody>';
foreach($report AS $line) {
    echo '<tr>';
    foreach($line AS $field) {
        echo '<td>' . $field . '</td>';
    }
    echo '</tr>';
}
echo '</tbody>';
?>
</table>