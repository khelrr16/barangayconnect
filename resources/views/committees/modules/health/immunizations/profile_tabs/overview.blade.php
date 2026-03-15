@php
    $completedScheduleCount = $scheduledImmunizations->where('status', 'Completed')->count();
    $nextScheduledImmunization = $scheduledImmunizations->first(fn ($schedule) => in_array($schedule['status'], ['Due now', 'Upcoming', 'Overdue'], true));
@endphp

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="bg-white border rounded-4 p-3 h-100">
            <div class="text-muted small">Current Age</div>
            <div class="fs-5 fw-semibold">{{ $currentAgeLabel }}</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="bg-white border rounded-4 p-3 h-100">
            <div class="text-muted small">Completed Schedules</div>
            <div class="fs-5 fw-semibold">{{ $completedScheduleCount }} / {{ $scheduledImmunizations->count() }}</div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="bg-white border rounded-4 p-3 h-100">
            <div class="text-muted small">Next Scheduled Immunization</div>
            @if($nextScheduledImmunization)
                <div class="fw-semibold">{{ $nextScheduledImmunization['title'] }}</div>
                <div class="small text-muted">{{ implode(', ', $nextScheduledImmunization['pending_medicines']) ?: implode(', ', $nextScheduledImmunization['medicines']) }}</div>
            @else
                <div class="fw-semibold">Complete</div>
                <div class="small text-muted">All scheduled doses recorded.</div>
            @endif
        </div>
    </div>
</div>

<div class="mb-4">
    <h6 class="fw-bold mb-3">Immunization Overview</h6>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Immunization Used</th>
                    <th>Total Used</th>
                    <th>Dates Administered</th>
                    <th>Next Due</th>
                </tr>
            </thead>

            <tbody>
                @forelse($overviewRows as $row)
                    <tr>
                        <td class="fw-semibold">{{ $row['name'] }}</td>
                        <td>{{ $row['total_used'] }}</td>
                        <td>
                            @if(!empty($row['dates_administered']))
                                {{ implode(', ', $row['dates_administered']) }}
                            @else
                                <span class="text-muted">No doses recorded</span>
                            @endif
                        </td>
                        <td>
                            @if($row['next_due'] === 'Complete')
                                <span class="badge text-bg-success">Complete</span>
                            @else
                                <div class="fw-semibold">{{ $row['next_due_label'] }}</div>
                                <div class="small text-muted">{{ $row['next_due'] }}</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No immunization medicines found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div>
    <h6 class="fw-bold mb-3">Scheduled Immunization List</h6>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Schedule</th>
                    <th>Immunizations</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @foreach($scheduledImmunizations as $schedule)
                    <tr>
                        <td>
                            <div class="fw-semibold">{{ $schedule['title'] }}</div>
                            <div class="small text-muted">{{ $schedule['window'] }}</div>
                            <div class="small text-muted">Due {{ $schedule['due_date'] }}</div>
                            @if($schedule['deadline_date'])
                                <div class="small text-muted">Window ends {{ $schedule['deadline_date'] }}</div>
                            @endif
                        </td>
                        <td>
                            <div>{{ implode(', ', $schedule['medicines']) }}</div>

                            @if(!empty($schedule['completed_medicines']))
                                <div class="small text-success mt-1">Completed: {{ implode(', ', $schedule['completed_medicines']) }}</div>
                            @endif

                            @if(!empty($schedule['pending_medicines']))
                                <div class="small text-muted mt-1">Pending: {{ implode(', ', $schedule['pending_medicines']) }}</div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $schedule['status_class'] }}">{{ $schedule['status'] }}</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>