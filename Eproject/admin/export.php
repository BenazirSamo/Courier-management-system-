<?php
include("../includes/agent-auth.php");
include("../config/db.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=report.xls");

$q = mysqli_query($conn,"SELECT * FROM couriers");
while($r = mysqli_fetch_assoc($q)){
    echo $r['tracking_no']."\t".$r['status']."\n";
}
