@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <!-- Error Message Container -->
        <div id="errorMessage" class="error-message"></div>

        <!-- Filter Section -->
        <div class="card flex-fill">
            <div class="card-body d-flex justify-content-between">
                <div>
                    <i class="fa-solid fa-filter"></i> Filters:
                </div>
                <div>
                    <select class="box-shadow" id="subdivision" name="subdivision">
                        <option value="">All Subdivisions</option>
                        @foreach($subdivisions as $subdivision)
                            <option value="{{ $subdivision }}" {{ $selectedSubdivision == $subdivision ? 'selected' : '' }}>
                                {{ $subdivision }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-success" id="applyFilterBtn" onclick="applyFilter()">Apply Filter</button>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row g-3 mb-4">
            <!-- Total Residents -->
            <div class="col-md-3">
                <div class="card text-white bg-primary shadow-sm rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-0" id="totalResidents">##</h3>
                            <small>Total Residents</small>
                        </div>
                        <i class="fa-solid fa-users fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Total Households -->
            <div class="col-md-3">
                <div class="card text-white bg-warning shadow-sm rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-0" id="totalHousehold">##</h3>
                            <small>Total Households</small>
                        </div>
                        <i class="fa-solid fa-house fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Employment Rate -->
            <div class="col-md-3">
                <div class="card text-white bg-success shadow-sm rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-0" id="laborForce">##%</h3>
                            <small>Labor Force</small>
                        </div>
                        <i class="fa-solid fa-briefcase fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>

            <!-- Registered Voters -->
            <div class="col-md-3">
                <div class="card text-white bg-danger shadow-sm rounded-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="fw-bold mb-0" id="totalRegistered">##</h3>
                            <small>Registered Voters</small>
                        </div>
                        <i class="fa-solid fa-heart fs-1 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4><i class="fa-solid fa-user"></i> Sex Distribution</h4>
                        <div class="chart">
                            <canvas id="sexChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4><i class="fa-solid fa-user"></i> Age Groups</h4>
                        <div class="chart">
                            <canvas id="ageChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4><i class="fa-solid fa-user"></i> Civil Status Distribution</h4>
                        <div class="chart">
                            <canvas id="civilStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4><i class="fa-solid fa-user"></i>Employment Status</h4>
                        <div class="chart">
                            <canvas id="employmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div id="loadingIndicator" class="loading" style="display: none;">
            <div class="loading-spinner"></div>
            <p>Loading chart data...</p>
        </div>

        <!-- Last Updated -->
        <div class="last-updated" id="lastUpdated"></div>
    </div>
@endsection

@push('styles')
    <style>
        .chart{
            height: 300px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let civilStatusChart = null;
        let sexChart = null;
        let ageChart = null;
        let employmentChart = null;

        const subdivisionSelect = document.getElementById('subdivision');
        const applyFilterBtn = document.getElementById('applyFilterBtn');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const errorMessage = document.getElementById('errorMessage');
        const lastUpdatedSpan = document.getElementById('lastUpdated');

        const totalResidentsEl = document.getElementById('totalResidents');
        const totalHouseholdEl = document.getElementById('totalHousehold');
        const laborForceEl = document.getElementById('laborForce');
        const totalRegisteredEl = document.getElementById('totalRegistered');

        document.addEventListener('DOMContentLoaded', function() {
            loadChartData();
            
            subdivisionSelect.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    applyFilter();
                }
            });
        });

        function applyFilter() {
            loadChartData();
        }

        async function loadChartData() {
            showLoading(true);
            hideError();

            const subdivision = subdivisionSelect.value;
            const url = new URL('{{ route("admin.dashboard.chart-data") }}', window.location.origin);
            
            if (subdivision) {
                url.searchParams.append('subdivision', subdivision);
            }

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                updateStatistics(data);
                updateSexChart(data.sex);
                updateAgeChart(data.age);
                updateCivilStatusChart(data.civil_status);
                updateEmploymentChart(data.employment);
                updateLastUpdated();

            } catch (error) {
                console.error('Error loading chart data:', error);
                showError('Failed to load chart data. Please try again.');
            } finally {
                showLoading(false);
            }
        }

        function updateStatistics(data) {
            const statistics = data.statistics;
            
            totalResidentsEl.textContent = statistics.residents || 0;
            totalHouseholdEl.textContent = statistics.households || 0;
            laborForceEl.textContent = statistics.laborForce || 0;
            totalRegisteredEl.textContent = statistics.registeredVoters || 0;
        }

        function updateSexChart(data) {
            const ctx = document.getElementById('sexChart').getContext('2d');
            
            if (sexChart) {
                sexChart.destroy();
            }

            sexChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.data,
                        backgroundColor: data.colors,
                        borderColor: 'white',
                        borderWidth: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    let percentage = ((value / data.total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '60%'
                }
            });
        }

        function updateAgeChart(data) {
            const ctx = document.getElementById('ageChart').getContext('2d');
            
            if (ageChart) {
                ageChart.destroy();
            }

            ageChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Residents',
                        data: data.data,
                        backgroundColor: data.colors.map(color => color + '80'), // Add transparency
                        borderColor: data.colors,
                        borderWidth: 2,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.chart.data.labels[context.dataIndex] || '';
                                    let value = context.raw || 0;
                                    let percentage = ((value / data.total) * 100).toFixed(1);
                                    return `Ages ${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : null;
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateCivilStatusChart(data) {
            const ctx = document.getElementById('civilStatusChart').getContext('2d');
            
            if (civilStatusChart) {
                civilStatusChart.destroy();
            }

            civilStatusChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Residents',
                        data: data.data,
                        backgroundColor: data.colors.map(color => color + '80'), // Add transparency
                        borderColor: data.colors,
                        borderWidth: 2,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.chart.data.labels[context.dataIndex] || '';
                                    let value = context.raw || 0;
                                    let percentage = ((value / data.total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    return Number.isInteger(value) ? value : null;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        function updateEmploymentChart(data) {
            const ctx = document.getElementById('employmentChart').getContext('2d');
            
            if (employmentChart) {
                employmentChart.destroy();
            }

            employmentChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Residents',
                        data: data.data,
                        backgroundColor: data.colors.map(color => color + '80'), // Add transparency
                        borderColor: data.colors,
                        borderWidth: 2,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.chart.data.labels[context.dataIndex] || '';
                                    let value = context.raw || 0;
                                    let percentage = ((value / data.total) * 100).toFixed(1);
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {  // Add this section
                            display: true,
                            anchor: 'end',
                            align: 'center',
                            offset: 200,
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: (value, context) => {
                                let label = context.chart.data.labels[context.dataIndex];
                                let percentage = ((value / data.total) * 100).toFixed(1);
                                return `${label}: ${percentage}%`;
                            },
                            backgroundColor: 'rgba(255, 255, 255, 0.7)',
                            borderRadius: 4
                        }
                    }
                }
            });
        }

        function showLoading(show) {
            if (show) {
                loadingIndicator.style.display = 'block';
                applyFilterBtn.disabled = true;
                subdivisionSelect.disabled = true;
            } else {
                loadingIndicator.style.display = 'none';
                applyFilterBtn.disabled = false;
                subdivisionSelect.disabled = false;
            }
        }

        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            
            setTimeout(() => {
                errorMessage.style.display = 'none';
            }, 5000);
        }

        function hideError() {
            errorMessage.style.display = 'none';
            errorMessage.textContent = '';
        }

        function updateLastUpdated() {
            const now = new Date();
            const formatted = now.toLocaleString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            lastUpdatedSpan.textContent = `Last updated: ${formatted}`;
        }
    </script>
@endpush
