@extends('layouts.committee')

@section('title', 'Health Dashboard')

@section('content')
	<div class="container-fluid mt-4">
		<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
			<div>
				<h4 class="fw-bold mb-0">Health Dashboard</h4>
				<small class="text-muted">Reporting period: {{ $periodLabel }}</small>
			</div>
			<div class="d-flex gap-2 no-print">
				<a href="{{ route('committee.health.dashboard.print', request()->query()) }}" class="btn btn-outline-secondary">
					<i class="fa-solid fa-print"></i> Print View
				</a>
				<a href="{{ route('committee.health.dashboard.pdf', request()->query()) }}" class="btn btn-primary">
					<i class="fa-solid fa-file-pdf"></i> Download PDF
				</a>
			</div>
		</div>

		<div class="alert alert-warning py-2 small">
			Monitoring status is grouped by <strong>last update date</strong> (no dedicated status history timestamp exists yet).
		</div>

		<div class="card shadow-sm mb-3 no-print">
			<div class="card-body">
				<form method="GET" action="{{ route('committee.health.dashboard') }}" class="row g-3 align-items-end">
					<div class="col-md-3">
						<label for="mode" class="form-label">View by</label>
						<select id="mode" name="mode" class="form-select">
							<option value="month" {{ $mode === 'month' ? 'selected' : '' }}>Month</option>
							<option value="year" {{ $mode === 'year' ? 'selected' : '' }}>Year</option>
						</select>
					</div>

					<div class="col-md-3">
						<label for="year" class="form-label">Year</label>
						<select id="year" name="year" class="form-select">
							@foreach($years as $yearOption)
								<option value="{{ $yearOption }}" {{ (int) $yearOption === (int) $year ? 'selected' : '' }}>{{ $yearOption }}</option>
							@endforeach
						</select>
					</div>

					<div class="col-md-3" id="monthWrapper">
						<label for="month" class="form-label">Month</label>
						<select id="month" name="month" class="form-select">
							@foreach($months as $monthOption)
								<option value="{{ $monthOption['value'] }}" {{ (int) $monthOption['value'] === (int) $month ? 'selected' : '' }}>
									{{ $monthOption['label'] }}
								</option>
							@endforeach
						</select>
					</div>

					<div class="col-md-3">
						<button type="submit" class="btn btn-success w-100">Apply Filter</button>
					</div>
				</form>
			</div>
		</div>

		<div class="row g-3 mb-3">
			<div class="col-md-4">
				<div class="card text-white bg-primary h-100">
					<div class="card-body">
						<h6 class="mb-1">Overall Medicines Used</h6>
						<h3 class="mb-0">{{ number_format($totals['overall_medicine_used']) }}</h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card text-white bg-success h-100">
					<div class="card-body">
						<h6 class="mb-1">Infants with Monitoring Status</h6>
						<h3 class="mb-0">{{ number_format($totals['monitoring_infants']) }}</h3>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<div class="card text-white bg-warning h-100">
					<div class="card-body">
						<h6 class="mb-1">Infants with Nutritional Status</h6>
						<h3 class="mb-0">{{ number_format($totals['nutritional_infants']) }}</h3>
					</div>
				</div>
			</div>
		</div>

		<div class="card shadow-sm mb-3">
			<div class="card-body">
				<h5 class="fw-semibold mb-3">Medicine Usage Graph</h5>
				<div
					id="medicineChartData"
					data-labels="{{ $medicineUsage->pluck('medicine_name')->map(fn ($label) => str_replace('|', '/', (string) $label))->join('|') }}"
					data-values="{{ $medicineUsage->pluck('overall_total')->join('|') }}"
				></div>
				<div class="chart-wrap">
					<canvas id="medicineUsageChart"></canvas>
				</div>
			</div>
		</div>

		<div class="card shadow-sm mb-3">
			<div class="card-body">
				<h5 class="fw-semibold mb-3">Overall Medicines Used</h5>
				<div class="table-responsive">
					<table class="table table-bordered align-middle mb-0">
						<thead class="table-light">
							<tr>
								<th>Medicine</th>
								<th class="text-center">Immunization Entries</th>
								<th class="text-center">Infant Direct Entries</th>
								<th class="text-center">Overall Used</th>
								<th class="text-center">Infants Covered</th>
							</tr>
						</thead>
						<tbody>
							@forelse($medicineUsage as $row)
								<tr>
									<td>{{ $row['medicine_name'] }}</td>
									<td class="text-center">{{ $row['immunization_total'] }}</td>
									<td class="text-center">{{ $row['direct_total'] }}</td>
									<td class="text-center fw-semibold">{{ $row['overall_total'] }}</td>
									<td class="text-center">{{ $row['overall_infants'] }}</td>
								</tr>
							@empty
								<tr>
									<td colspan="5" class="text-center text-muted">No medicine records for this period.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div class="row g-3 mb-3">
			<div class="col-lg-4">
				<div class="card shadow-sm h-100">
					<div class="card-body">
						<h5 class="fw-semibold mb-3">Monitoring Status Summary</h5>
						<ul class="list-group list-group-flush">
							@forelse($monitoringStatusSummary as $status => $count)
								<li class="list-group-item d-flex justify-content-between px-0">
									<span>{{ $status }}</span>
									<strong>{{ $count }}</strong>
								</li>
							@empty
								<li class="list-group-item px-0 text-muted">No monitoring statuses for this period.</li>
							@endforelse
						</ul>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="card shadow-sm h-100">
					<div class="card-body">
						<h5 class="fw-semibold mb-3">Monitoring Status Per Infant</h5>
						<div class="table-responsive">
							<table class="table table-sm table-bordered align-middle mb-0">
								<thead class="table-light">
									<tr>
										<th>Infant</th>
										<th>Status</th>
										<th>Updated At</th>
									</tr>
								</thead>
								<tbody>
									@forelse($monitoringInfants as $infant)
										<tr>
											<td>{{ $infant->name }}</td>
											<td>{{ $infant->status }}</td>
											<td>{{ optional($infant->updated_at)->format('M d, Y h:i A') }}</td>
										</tr>
									@empty
										<tr>
											<td colspan="3" class="text-center text-muted">No infant monitoring status records for this period.</td>
										</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row g-3">
			<div class="col-lg-4">
				<div class="card shadow-sm h-100">
					<div class="card-body">
						<h5 class="fw-semibold mb-3">Nutritional Status Summary</h5>
						<ul class="list-group list-group-flush">
							@forelse($nutritionalStatusSummary as $status => $count)
								<li class="list-group-item d-flex justify-content-between px-0">
									<span>{{ $status }}</span>
									<strong>{{ $count }}</strong>
								</li>
							@empty
								<li class="list-group-item px-0 text-muted">No nutritional statuses for this period.</li>
							@endforelse
						</ul>
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="card shadow-sm h-100">
					<div class="card-body">
						<h5 class="fw-semibold mb-3">Nutritional Status Per Infant (Latest in Period)</h5>
						<div class="table-responsive">
							<table class="table table-sm table-bordered align-middle mb-0">
								<thead class="table-light">
									<tr>
										<th>Infant</th>
										<th>Category</th>
										<th>Status</th>
										<th>Assessment Date</th>
									</tr>
								</thead>
								<tbody>
									@forelse($latestNutritionalByInfant as $record)
										<tr>
											<td>{{ $record->infant_name }}</td>
											<td>{{ str_replace('_', ' ', (string) $record->category) }}</td>
											<td>{{ $record->status ?: '-' }}</td>
											<td>{{ optional($record->assessment_date)->format('M d, Y') }}</td>
										</tr>
									@empty
										<tr>
											<td colspan="4" class="text-center text-muted">No nutritional assessment records for this period.</td>
										</tr>
									@endforelse
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('styles')
	<style>
		.chart-wrap {
			height: 320px;
		}
	</style>
@endpush

@push('scripts')
	<script>
		const modeSelect = document.getElementById('mode');
		const monthWrapper = document.getElementById('monthWrapper');

		function toggleMonthVisibility() {
			if (modeSelect.value === 'year') {
				monthWrapper.classList.add('d-none');
			} else {
				monthWrapper.classList.remove('d-none');
			}
		}

		modeSelect.addEventListener('change', toggleMonthVisibility);
		toggleMonthVisibility();

		const chartDataHolder = document.getElementById('medicineChartData');
		const medicineLabels = chartDataHolder?.dataset.labels ? chartDataHolder.dataset.labels.split('|') : [];
		const medicineTotals = chartDataHolder?.dataset.values
			? chartDataHolder.dataset.values.split('|').map((value) => Number(value))
			: [];

		const chartCanvas = document.getElementById('medicineUsageChart');
		if (chartCanvas && medicineLabels.length && window.Chart) {
			new window.Chart(chartCanvas, {
				type: 'bar',
				data: {
					labels: medicineLabels,
					datasets: [{
						label: 'Medicine usage count',
						data: medicineTotals,
						backgroundColor: [
							'#0d6efd', '#198754', '#ffc107', '#fd7e14', '#6f42c1', '#20c997', '#dc3545', '#0dcaf0'
						],
						borderWidth: 1,
					}],
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					scales: {
						y: {
							beginAtZero: true,
							ticks: {
								precision: 0,
							},
						},
					},
					plugins: {
						legend: {
							display: false,
						},
						datalabels: {
							anchor: 'end',
							align: 'end',
							color: '#111',
							formatter: (value) => value,
						},
					},
				},
			});
		}
	</script>
@endpush
