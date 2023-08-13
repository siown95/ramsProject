<?php
$start_time = microtime(TRUE); // 정보 수집 시작 시간
$operating_system = PHP_OS_FAMILY;
if ($operating_system === 'Windows') {
	// Win CPU
	$wmi = new COM('WinMgmts:\\\\.');
	$cpus = $wmi->InstancesOf('Win32_Processor');
	$cpuload = 0;
	$cpu_count = 0;
	foreach ($cpus as $key => $cpu) {
		$cpuload += $cpu->LoadPercentage;
		$cpu_count++;
	}
	// WIN MEM
	$res = $wmi->ExecQuery('SELECT FreePhysicalMemory,FreeVirtualMemory,TotalSwapSpaceSize,TotalVirtualMemorySize,TotalVisibleMemorySize FROM Win32_OperatingSystem');
	$mem = $res->ItemIndex(0);
	$memtotal = round($mem->TotalVisibleMemorySize / 1000000, 2); // GB 메모리 총 용량
	$memavailable = round($mem->FreePhysicalMemory / 1000000, 2); // GB 메모리 사용 가능 용량
	$memused = round($memtotal - $memavailable, 2); // GB 메모리 사용중인 용량
	// WIN CONNECTIONS
	$connections80 = shell_exec('netstat -nt | findstr :80 | findstr ESTABLISHED | find /C /V ""'); // 80 현재 연결 수
	$connections443 = shell_exec('netstat -nt | findstr :443 | findstr ESTABLISHED | find /C /V ""');  // 443 현재 연결 수
	$totalconnections80 = shell_exec('netstat -nt | findstr :80 | find /C /V ""'); // 80 연결 수
	$totalconnections443 = shell_exec('netstat -nt | findstr :443 | find /C /V ""'); // 443 연결 수

	$connections = $connections80 + $connections443; // 현재 접속
	$totalconnections = $totalconnections80 + $totalconnections443; // 누적 접속
} else {
	return false;
}
$memusage = round(($memused / $memtotal) * 100, 2); // % 메모리 사용률
$phpload = round(memory_get_usage() / 1000000, 2); // GB PHP 사용률

$diskfree = round(disk_free_space(".") / 1000000000); // GB 저장장치 여유 용량
$disktotal = round(disk_total_space(".") / 1000000000); // GB 저장장치 총 용량
$diskused = round($disktotal - $diskfree); // GB 저장장치 사용 용량
$diskusage = round($diskused / $disktotal * 100); // % 저장장치 사용률

$end_time = microtime(TRUE); // 정보 수집 종료 시간
$time_taken = $end_time - $start_time; // 종료시간 - 시작시간
$total_time = round($time_taken, 3); // 걸린시간

$cpuload . " %";
if ($cpuload < 33) {
	$result['cpu_stat'] = '<i class="fa-solid fa-circle text-success"></i>';
} else if ($cpuload < 66) {
	$result['cpu_stat'] = '<i class="fa-solid fa-circle text-warning"></i>';
} else {
	$result['cpu_stat'] = '<i class="fa-solid fa-circle text-danger"></i>';
}
$memusage . " %";
if ($memusage < 33) {
	$result['mem_stat'] = '<i class="fa-solid fa-circle text-success"></i>';
} else if ($memusage < 66) {
	$result['mem_stat'] = '<i class="fa-solid fa-circle text-warning"></i>';
} else {
	$result['mem_stat'] = '<i class="fa-solid fa-circle text-danger"></i>';
}
$diskusage . " %";
if ($diskusage < 33) {
	$result['disk_stat'] = '<i class="fa-solid fa-circle text-success"></i>';
} else if ($diskusage < 66) {
	$result['disk_stat'] = '<i class="fa-solid fa-circle text-warning"></i>';
} else {
	$result['disk_stat'] = '<i class="fa-solid fa-circle text-danger"></i>';
}
?>
<div class="container-fluid">
	<div class="row mt-2 mb-2">
		<div class="col-12">
			<div class="card border-left-primary shadow">
				<div class="card-header">서버 모니터링</div>
				<div class="card-body">
					<div class="row">
						<div class="col-xl-3 col-md-4 mb-2">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">CPU 이용</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= $cpuload ?> %</div>
										</div>
										<div class="col-auto"><?= $result['cpu_stat'] ?></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4 mb-2">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">현재 연결 수</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= (!empty($connections) ? number_format($connections) : 0) ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4 mb-2">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">총 연결 수</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= (!empty($totalconnections) ? number_format($totalconnections) : 0) ?></div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4 mb-2">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">페이지 사용량</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= $phpload ?> GB</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4 mb-2">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">메모리 이용</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= (!empty($memusage) ? number_format($memusage) : 0) ?>%</div>
										</div>
										<div class="col-auto"><?= $result['mem_stat'] ?></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4 mb-2">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">메모리 용량</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= $memtotal ?> GB</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4 mb-2">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">메모리 사용</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= $memused ?> GB</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4 mb-2">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">메모리 여유</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= $memavailable ?> GB</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">디스크 이용</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= (!empty($diskusage) ? number_format($diskusage) : 0) ?>%</div>
										</div>
										<div class="col-auto"><?= $result['disk_stat'] ?></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">디스크 용량</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= (!empty($disktotal) ? number_format($disktotal) : 0) ?> GB</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">디스크 사용용량</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= (!empty($diskused) ? number_format($diskused) : 0) ?> GB</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-xl-3 col-md-4">
							<div class="card border-left-primary shadow h-100 py-2">
								<div class="card-body">
									<div class="row no-gutters align-items-center">
										<div class="col mr-2">
											<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">디스크 여유용량</div>
											<div class="h5 mb-0 font-weight-bold text-gray-800"><?= (!empty($diskfree) ? number_format($diskfree) : 0) ?> GB</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer text-end">요청시간 : <?= date('Y-m-d H:i:s') ?> / 처리 소요시간 : <?= $total_time ?> 초</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<div class="card border-left-primary shadow">
				<div class="card-header">CPU 사용률</div>
				<div class="card-body">
					<canvas id="chart_cpu"></canvas>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card border-left-primary shadow">
				<div class="card-header">메모리 사용률</div>
				<div class="card-body">
					<canvas id="chart_memory"></canvas>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card border-left-primary shadow">
				<div class="card-header">디스크 사용률</div>
				<div class="card-body">
					<canvas id="chart_disk"></canvas>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card border-left-primary shadow">
				<div class="card-header">연결 수</div>
				<div class="card-body">
					<canvas id="chart_connection"></canvas>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var ctx1 = document.getElementById("chart_cpu");
	var ctx2 = document.getElementById("chart_memory");
	var ctx3 = document.getElementById("chart_disk");
	var ctx4 = document.getElementById("chart_connection");
	var option = {
		maintainAspectRatio: true,
		tooltips: {
			backgroundColor: "rgb(255,255,255)",
			bodyFontColor: "#858796",
			borderColor: '#dddfeb',
			borderWidth: 1,
			xPadding: 15,
			yPadding: 15,
			displayColors: false,
			caretPadding: 10,
		},
		legend: {
			display: false
		},
		cutoutPercentage: 80,
	};

	var cpuChart = new Chart(ctx1, {
		type: 'pie',
		data: {
			labels: ["CPU 사용량", "CPU 미사용량"],
			datasets: [{
				data: [<?= $cpuload ?>, <?= (100 - $cpuload) ?>],
				backgroundColor: ['#4e73df', '#1cc88a'],
				hoverBackgroundColor: ['#2e59d9', '#17a673'],
				hoverBorderColor: "rgba(234, 236, 244, 1)",
			}],
		},
		options: option,
	});

	var ramChart = new Chart(ctx2, {
		type: 'pie',
		data: {
			labels: ["메모리 총 용량", "메모리 사용 용량", "메모리 미사용 용량"],
			datasets: [{
				data: [<?= $memtotal ?>, <?= $memused ?>, <?= $memavailable ?>],
				backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
				hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
				hoverBorderColor: "rgba(234, 236, 244, 1)",
			}],
		},
		options: option,
	})

	var diskChart = new Chart(ctx3, {
		type: 'pie',
		data: {
			labels: ["디스크 총 용량", "디스크 사용 용량", "디스크 잔여 용량"],
			datasets: [{
				data: [<?= $disktotal ?>, <?= $diskused ?>, <?= $diskfree ?>],
				backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
				hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
				hoverBorderColor: "rgba(234, 236, 244, 1)",
			}],
		},
		options: option,
	});

	var connectionChart = new Chart(ctx4, {
		type: 'pie',
		data: {
			labels: ["총 연결 수", "현재 연결 수"],
			datasets: [{
				data: [<?= $totalconnections ?>, <?= $connections ?>],
				backgroundColor: ['#4e73df', '#1cc88a'],
				hoverBackgroundColor: ['#2e59d9', '#17a673'],
				hoverBorderColor: "rgba(234, 236, 244, 1)",
			}],
		},
		options: option,
	});
</script>