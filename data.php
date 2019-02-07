<?php

header('Content-Type: application/json');

include 'dbh.inc.php';


$sql_tid = sprintf("SELECT datediff (minute, r.Created, t.Resolved) as tid
                    from Requests r
                    inner join Tickets t on (r.id = t.id)
                    where t.Resolved is not null
                    and r.RestrictedTeamId in (2, 3, 26, 34, 4, 33, 66, 37, 37, 28, 35, 29, 38)
                    and r.Created > DATEADD(week, -1, getdate())");

$tid_result = sqlsrv_query($connect, $sql_tid);

$data = array();

foreach ($tid_result as $row_tid) {
  $data[] = $row_tid;
}

print json_encode($data);
