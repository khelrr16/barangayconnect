@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="card mb-2">
            <div class="card-body d-flex justify-content-between">
                <span><i class="bi bi-funnel"></i> Filters:</span>
                <form method="GET" action="{{ route('admin.dashboard') }}">
                    <!-- <select name="duration" onchange="this.form.submit()">
                        <option value="0">All Time</option>
                        <option value="1">This Month</option>
                        <option value="2">This Quarter</option>
                        <option value="3">This Year</option>
                    </select> -->

                    <select name="subdivision" onchange="this.form.submit()">
                        <option value="0" {{ request('subdivision', 0) == 0 ? 'selected' : '' }} >All Subdivisions</option>
                        <option value="1" {{ request('subdivision', 0) == 1 ? 'selected' : '' }}>Conpil I Village</option>
                        <option value="2" {{ request('subdivision', 0) == 2 ? 'selected' : '' }}>Conpil III Executive</option>
                        <option value="3" {{ request('subdivision', 0) == 3 ? 'selected' : '' }}>Console 1 Village</option>
                        <option value="4" {{ request('subdivision', 0) == 4 ? 'selected' : '' }}>Greatland Village</option>
                        <option value="5" {{ request('subdivision', 0) == 5 ? 'selected' : '' }}>Guevara Subdivision</option>
                        <option value="6" {{ request('subdivision', 0) == 6 ? 'selected' : '' }}>Pacita 2A</option>
                        <option value="7" {{ request('subdivision', 0) == 7 ? 'selected' : '' }}>Pacita 2B</option>
                    </select>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <h4><i class="bi bi-person"></i> Gender Distribution</h4>
                        <div class="chart-container">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 d-flex">
                <div class="card flex-fill">
                    <div class="card-body">
                        <h4><i class="bi bi-person"></i> Civil Status Distribution</h4>
                        <div class="chart-container">
                            <canvas id="civilStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .chart-container {
            position: relative;
            height: 300px;   /* Force same height */
            width: 100%;
        }
    </style>
@endpush

@push('scripts')
    <script type="module">
        const cGender = document.getElementById('genderChart');
        const cCivilStatus = document.getElementById('civilStatusChart');

        new Chart(cGender, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    label: 'Residents',
                    data: [{{ $gender['male'] }}, {{ $gender['female'] }}],
                    backgroundColor: ["blue", "red"],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

        const civilStatusData = @json($civil_status);
        new Chart(cCivilStatus, {
            type: 'bar',
            data: {
                labels: Object.keys(civilStatusData),
                datasets: [{
                    data: Object.values(civilStatusData),
                    backgroundColor: [
                        "rgba(17, 33, 207, 0.8)", 
                        "rgba(255, 66, 176, 0.85)",
                        "rgba(54, 162, 235, 0.8)",
                        "rgba(255, 206, 86, 0.8)"
                    ],
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // IMPORTANT
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush
