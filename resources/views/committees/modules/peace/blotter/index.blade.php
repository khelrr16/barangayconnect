@extends('layouts.committee')

@section('title', 'Blotter')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-4">
            <div>
                <h2 class="fw-bolder m-0">
                    <i class="fa-solid fa-scale-balanced"></i> Peace & Order
                </h2>
                <small class="text-muted">Committee on Peace & Order</small>
            </div>
            <div>
                <a href="{{ route('committee.peace.blotter.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus"></i> New Case
                </a>
            </div>

        </div>

        @forelse($records as $record)
            @php
                $status = strtoupper((string) $record->status);
                $statusClass = match ($status) {
                    'FILED' => 'bg-primary',
                    'MEDIATION' => 'bg-info',
                    'HEARING', 'SETTLED' => 'bg-success',
                    'UNRESOLVED', 'ENDORSED' => 'bg-secondary',
                    default => 'bg-light text-dark border',
                };
            @endphp

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-body position-relative" style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap fs-5">
                            <i class="fa-solid fa-scale-balanced text-secondary"></i>

                            <strong>{{ $record->blotter_number }}</strong>

                            <span class="badge {{ $statusClass }}">{{ $record->status }}</span>
                            <span class="badge bg-light text-dark border">{{ $record->case_type }}</span>
                            @if($record->documents_count ?? null)
                                <span class="badge bg-light text-dark border">
                                    <i class="fa-solid fa-paperclip"></i> {{ $record->documents_count }}
                                </span>
                            @endif
                        </div>

                        <div class="d-flex gap-2 position-relative z-1">
                            <a href="{{ route('committee.peace.blotter.show', $record->id) }}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('committee.peace.blotter.edit', $record->id) }}" class="btn btn-warning btn-sm text-white" disabled>
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <a href="{{ route('committee.peace.blotter.destroy', $record->id) }}" class="btn btn-danger btn-sm" disabled>
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('committee.peace.blotter.show', $record->id) }}" class="stretched-link" aria-label="View blotter {{ $record->blotter_number }}"></a>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <div class="p-3 rounded bg-light border">
                                <small class="text-muted">Complainant</small>
                                <div class="fw-semibold">{{ $record->complainant_name }}</div>
                                <small class="text-muted">{{ $record->complainant_address }}</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="p-3 rounded bg-warning bg-opacity-10 border">
                                <small class="text-muted">Respondent</small>
                                <div class="fw-semibold text-danger">{{ $record->respondent_name }}</div>
                                <small class="text-muted">{{ $record->respondent_address }}</small>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted mb-3">{{ \Illuminate\Support\Str::limit($record->case_description, 220) }}</p>

                    <div class="d-flex flex-wrap gap-4 text-muted small">
                        <div>
                            <i class="fa-regular fa-calendar"></i>
                            Filed: {{ optional($record->date_filled)->format('Y-m-d') ?? '-' }}
                        </div>

                        <div>
                            <i class="fa-solid fa-user"></i>
                            {{ $record->assignedOfficial?->name ?? 'Unassigned' }}
                        </div>

                        @if($record->hearing_datetime)
                            <div class="text-primary">
                                <i class="fa-regular fa-clock"></i>
                                Hearing: {{ $record->hearing_datetime->format('Y-m-d g:i A') }}
                                @if($record->hearing_venue)
                                    ({{ $record->hearing_venue }})
                                @endif
                            </div>
                        @endif

                        <div>
                            <i class="fa-solid fa-paperclip"></i>
                            Attachments: {{ $record->documents_count }}
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm border-0">
                <div class="card-body text-center text-muted">
                    No blotter records yet.
                </div>
            </div>
        @endforelse
    </div>
@endsection
