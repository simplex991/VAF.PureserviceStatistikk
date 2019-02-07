<?php
include 'dbh.inc.php';

// Sjekker om uke har en verdi
if ($_GET['uke']) {
	$uke = $_GET['uke'];
}else {
	$uke = 1000;
}
?>
<!doctype html>
<html lang="en" class="no-js">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,700' rel='stylesheet' type='text/css'>
	<title>S-PS - Mandal</title>
	<link rel="stylesheet" href="css/main.css"> <!-- Resource style -->
	<link rel="stylesheet" href="css-bootstrap/bootstrap.css">
	<link rel="stylesheet" href="css-bootstrap/bootstrap-grid.css">
	<link rel="stylesheet" href="css-bootstrap/bootstrap-reboot.css">
	<script src="http://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.js" charset="utf-8"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" charset="utf-8"></script>
	<script src="js/modernizr.js"></script> <!-- Modernizr -->
</head>
<body>
  <?php
  include 'array-variabler.php';

    $sql = "SELECT status, sortering, count(status) as antall
            FROM (select CASE StatusId
							when 1 then 'NYE SAKER'
							when 2 then 'ÅPNE SAKER'
							when 3 then 'ÅPNE SAKER'
							when 4 then 'ÅPNE SAKER'
							when 5 then 'ÅPNE SAKER'
							when 6 then 'ÅPNE SAKER'
							when 16 then 'ÅPNE SAKER'
							when 17 then 'ÅPNE SAKER'
							when 18 then 'ÅPNE SAKER'
							when 19 then 'ÅPNE SAKER'
            else 'LØSTE SAKER'
            end as status,
						CASE StatusId
							when 1 then 'A'
							when 2 then 'B'
							when 3 then 'B'
							when 4 then 'B'
							when 5 then 'B'
							when 6 then 'B'
							when 16 then 'B'
							when 17 then 'B'
							when 18 then 'B'
							when 19 then 'B'
            else 'C'
            end as sortering
            from Requests
            where RestrictedTeamId IN(37)
						and Created > DATEADD(week, -(".$uke."), GETDATE())) as tabell
            group by status, sortering
						order by sortering";

		// Henter GJENNOMSNITTLIG brukt tid på saker den siste uken
		$sql_tid_uke1 = "SELECT datediff (minute, r.Created, t.Resolved) as tid
								from Requests r
								inner join Tickets t on (r.id = t.id)
								where t.Resolved is not null
   							and r.RestrictedTeamId in (37)
   							and r.Created > DATEADD(week, -1, getdate())";

		// Henter gjenneomsnittlig brukt tid på saker de 2 siste ukene
		$sql_tid_uke2 = "SELECT datediff (minute, r.Created, t.Resolved) as tid
								from Requests r
								inner join Tickets t on (r.id = t.id)
								where t.Resolved is not null
						   	and r.RestrictedTeamId in (37)
						   	and r.Created > DATEADD(week, -2, getdate())
								and r.Created < DATEADD(week, -1, getdate())";

		$sql_tid_uke3 = "SELECT datediff (minute, r.Created, t.Resolved) as tid
								from Requests r
								inner join Tickets t on (r.id = t.id)
								where t.Resolved is not null
								and r.RestrictedTeamId in (37)
								and r.Created > DATEADD(week, -3, getdate())
								and r.Created < DATEADD(week, -2, getdate())";

		$sql_tid_uke4 = "SELECT datediff (minute, r.Created, t.Resolved) as tid
								from Requests r
								inner join Tickets t on (r.id = t.id)
								where t.Resolved is not null
								and r.RestrictedTeamId in (37)
								and r.Created > DATEADD(week, -4, getdate())
								and r.Created < DATEADD(week, -3, getdate())";



    $result = sqlsrv_query($connect, $sql);

		$tid_result_uke1 = sqlsrv_query($connect, $sql_tid_uke1);

		$tid_result_uke2 = sqlsrv_query($connect, $sql_tid_uke2);

		$tid_result_uke3 = sqlsrv_query($connect, $sql_tid_uke3);

		$tid_result_uke4 = sqlsrv_query($connect, $sql_tid_uke4);
  ?>
	<header class="cd-main-header"> <!-- .cd-main-header -->
		<span class="navbar-brand">S-PS</span>
		<a href="#0" class="cd-nav-trigger"><span></span></a>
		<nav class="cd-nav">
      <ul class="cd-top-nav">
         <li><a href="https://pureservice.vaf.no" target="_blank">PureService</a></li>
         <li class="has-children account">
            <a href="#0" style="text-decoration: none; color:#fff;">
               <img src="img/cd-default-avatar.png" alt="avatar">
               Account
            </a>

            <ul>
               <li><a href="#0">My Account</a></li>
               <li><a href="#0">Settings</a></li>
            </ul>
         </li>
      </ul>
   </nav>
	</header>
	<main class="cd-main-content">
		<?php include 'includes/navbar.php'; ?> <!-- Navbar fil -->
		<div class="content-wrapper">
			<div class="container-fluid">
				<!-- Velge periode -->
				<form class="periode" action="." method="get">
  				<div class="form-row align-items-center">
    				<div class="col-auto my-1">
      				<select class="custom-select mr-sm-2" name="uke">
        				<option selected value="">Velg Periode...</option>
        				<option value="1">Uke</option>
        				<option value="4">Måned</option>
        				<option value="52">År</option>
								<option value="1000">Since the beginning of Time</option>
      				</select>
    				</div>
    				<div class="col-auto my-1">
      				<button type="submit" class="btn btn-primary">Oppdater</button>
    				</div>
  				</div>
				</form>
				<?php
				$valgt_periode = "";

				if ($_GET['uke'] == 1) {
					$valgt_periode = "DENNE UKEN";
				}elseif ($_GET['uke'] == 4) {
					$valgt_periode = "DENNE MÅNEDEN";
				}elseif ($_GET['uke'] == 52) {
					$valgt_periode = "SISTE ÅRET";
				}else {
					$valgt_periode = "";
				}
				?>
				<!-- Slutt på periode -->
				<div class="row">
						<?php
							$number = 1;
			        while( $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC) ) {
								$saker_undertekst = ""; // Declaring of $saker_undertekst

								if ($row['status'] == "NYE SAKER") {
									$saker_undertekst = "TILDELT ".$avdelinger['Mandal']." OG OPPRETTET ".$valgt_periode."";
								}elseif ($row['status'] == "ÅPNE SAKER") {
									$saker_undertekst = "TILDELT ".$avdelinger['Mandal']."";
								}elseif ($row['status'] == "LØSTE SAKER") {
									$saker_undertekst = "TILDELT ".$avdelinger['Mandal']." OG LØST ".$valgt_periode."";
								}else {
									$saker_undertekst = "Teksten lastet ikke, noe gikk feil";
								}
								echo "<div class='saker col-md-3' style='margin: 0 7px 7px 0; background-color:#fff;'>";
			          echo "<h2 style='color:#595959;' class='text-center'><b> ".$row['status']." </b></h2>";
								echo "<h5 class='subtitle text-center' style='color:#a6a6a6;'><small><b>".$saker_undertekst."</b></small></h5>";
			          echo "<h1 class='count text-center' style='margin: 6.3rem 0 6.3rem 0;'><span class='count' style='font-size-adjust: inherit;'>".$row['antall']."</h1></span>";
								echo "</div>";
			        }
			        ?>
					</div>
					<div class="row">
						<div class="gjen col-md-3" style="margin: 0 7px 7px 0; background-color:#fff;">
							<h2 style='color:#595959;' class='text-center'><b> GJENNOMSNITTLIG LØSNINGSTID </b></h2>
							<h5 class='subtitle text-center' style='color:#a6a6a6;'><small><b> FOR SAKER SOM ER TILDELT OG LØST FORRIGE 4 UKER </b></small></h5>
							<canvas id="myChart" width="300" height="300"></canvas>
						</div>

						<?php
						////////////
						// Uke 1 //
						///////////

						$avg_uke1 = array();
						while ($rowTid_uke1 = sqlsrv_fetch_array($tid_result_uke1, SQLSRV_FETCH_ASSOC)) {
							$avg_uke1[] = $rowTid_uke1;
						}

						foreach ($avg_uke1 as $uke1) {
							$min_uke1[] = $uke1['tid'];
						}
						$sum_week1 = array_sum($min_uke1) / count($min_uke1);

						////////////
						// Uke 2 //
						///////////
						$avg_uke2 = array();
						while ($rowTid_uke2 = sqlsrv_fetch_array($tid_result_uke2, SQLSRV_FETCH_ASSOC)) {
							$avg_uke2[] = $rowTid_uke2;
						}

						foreach ($avg_uke2 as $uke2) {
							$min_uke2[] = $uke2['tid'];
						}
						$sum_week2 = array_sum($min_uke2) / count($min_uke2);

						////////////
						// Uke 3 //
						///////////
						$avg_uke3 = array();
						while ($rowTid_uke3 = sqlsrv_fetch_array($tid_result_uke3, SQLSRV_FETCH_ASSOC)) {
							$avg_uke3[] = $rowTid_uke3;
						}

						foreach ($avg_uke3 as $uke3) {
							$min_uke3[] = $uke3['tid'];
						}
						$sum_week3 = array_sum($min_uke3) / count($min_uke3);

						////////////
						// Uke 4 //
						///////////
						$avg_uke4 = array();
						while ($rowTid_uke4 = sqlsrv_fetch_array($tid_result_uke4, SQLSRV_FETCH_ASSOC)) {
							$avg_uke4[] = $rowTid_uke4;
						}

						foreach ($avg_uke4 as $uke4) {
							$min_uke4[] = $uke4['tid'];
						}
						$sum_week4 = array_sum($min_uke4) / count($min_uke4);
						?>
					</div>
					<?php
					// Henter Uke
					$current_week = (int)date("W");
					$week_back_1 = $current_week - 1;
					$week_back_2 = $current_week - 2;
					$week_back_3 = $current_week - 3;
					?>

			</div>

		</div>
		</div> <!-- .content-wrapper -->
		<div class="fixed-bottom text-right">
			<div class="container-fluid">
				<span class="text-muted"><?php echo $version ?></span>
			</div>
		</div>
	</main> <!-- .cd-main-content -->
	<script>
		let myChart = document.getElementById('myChart').getContext('2d');

		// Global Options
		Chart.defaults.global.defaultFontFamily = 'Arial';
		Chart.defaults.global.defaultFontSize = 14;
		Chart.defaults.global.defaultFontColor = '#777';

		let time_chart = new Chart(myChart, {
			type:'line',
			data:{
				labels:[<?php echo json_encode($week_back_3)?>, <?php echo json_encode($week_back_2)?>, <?php echo json_encode($week_back_1)?>, <?php echo json_encode($current_week)?>],
				datasets:[{
					label:'Minutes',
					data:[
						<?php echo json_encode($sum_week1);?>,
						<?php echo json_encode($sum_week2);?>,
						<?php echo json_encode($sum_week3);?>,
						<?php echo json_encode($sum_week4);?>
					],
					backgroundColor:[
						'rgb(23, 23, 200, 0.6)',
						'rgba(54, 162, 235, 0.6)',
						'rgba(255, 206, 86, 0.6)',
						'rgba(75, 192, 192, 0.6)',
						'rgba(153, 102, 255, 0.6)',
						'rgba(255, 159, 64, 0.6)',
						'rgba(255, 99, 132, 0.6)'
					],
					borderWidth:1,
					borderColor:'#777',
					hoverBorderWidth:3,
					hoverBorderColor:'#000'
				}]
			},
			options:{
				title:{
					display:false,
					fontSize:20
				},
				legend:{
					display:false,
				},
				layout:{
					padding:{
						left:0,
						right:0,
						bottom:0,
						top:0
					}
				},
				tooltips:{
					enabled:true
				}
			}
		});
	</script>
<!-- <script src="js/line-db-php.js" charset="utf-8"></script> -->
<script src="js/jquery-2.1.4.js"></script>
<script src="js/jquery.menu-aim.js"></script>
<script src="js/main.js"></script> <!-- Resource jQuery -->
<?php sqlsrv_close($connect); ?>
</body>
</html>
