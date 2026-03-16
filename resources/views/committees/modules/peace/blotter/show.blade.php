@extends('layouts.committee')

@section('title', 'Blotter')

@section('content')

<div class="container my-4">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('committee.peace.blotter.index') }}" class="text-decoration-none text-dark">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="card shadow-sm border-0 p-4">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div class="d-flex align-items-center gap-3">
                <i class="fa-solid fa-scale-balanced fs-4 text-secondary"></i>

                <h5 class="mb-0 fw-bold">{{ $blotter->blotter_number }}</h5>

                <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                    {{ strtoupper($blotter->status) }}
                </span>

                <span class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-2">
                    {{ strtoupper($blotter->case_type) }}
                </span>


                <button type="button" class="badge btn btn-light rounded-pill text-black px-3 py-2" data-bs-toggle="modal" data-bs-target="#documentsModal">
                    <i class="fa-solid fa-paperclip"></i> {{ $blotter->documents_count }}
                </button>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-warning text-white">
                    <i class="fa-solid fa-pen"></i> Edit
                </button>

                <button class="btn btn-danger">
                    <i class="fa-solid fa-trash"></i> Delete
                </button>
            </div>

        </div>

        <!-- COMPLAINANT / RESPONDENT -->
        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <div class="p-3 rounded border-start border-4 border-primary bg-primary bg-opacity-10">
                    <small class="text-muted text-uppercase">Complainant</small>
                    <h6 class="fw-bold mb-0">{{ $blotter->complainant_name }}</h6>
                    <small class="text-muted">{{ $blotter->complainant_address }}</small>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-3 rounded border-start border-4 border-warning bg-warning bg-opacity-10">
                    <small class="text-muted text-uppercase">Respondent</small>
                    <h6 class="fw-bold mb-0">{{ $blotter->respondent_name }}</h6>
                    <small class="text-muted">{{ $blotter->respondent_address }}</small>
                </div>
            </div>

        </div>

        <!-- CASE INFO -->
        <div class="row g-3 mb-4">

            <div class="col-md-3">
                <div class="p-3 bg-light rounded border">
                    <small class="text-muted">
                        <i class="fa-regular fa-calendar"></i> Date Filed
                    </small>
                    <div class="fw-semibold">{{ $blotter->filed_at->format('M d, Y') }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 bg-light rounded border">
                    <small class="text-muted">
                        <i class="fa-solid fa-user"></i> Assigned To
                    </small>
                    <div class="fw-semibold">{{ $blotter->assignedOfficial?->name ?? 'Unassigned' }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 bg-light rounded border">
                    <small class="text-muted">
                        <i class="fa-regular fa-clock"></i> Date Updated
                    </small>
                    <div class="fw-semibold">{{ $blotter->updated_at->format('M d, Y') }}</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="p-3 bg-light rounded border">
                    <small class="text-muted">
                        <i class="fa-regular fa-calendar"></i> Hearing Date
                    </small>
                    <div class="fw-semibold">{{ $blotter->hearing_datetime?->format('M d, Y') ?? '-' }}</div>
                </div>
            </div>

        </div>

        <!-- DESCRIPTION -->
        <div class="mb-4">

            <div class="p-3 bg-light rounded border">
                <h6 class="fw-bold mb-3">
                    <i class="fa-regular fa-file-lines"></i> Case Description
                </h6>

                {{ $blotter->case_description }}
            </div>
        </div>

        <!-- HEARING CARD -->
        @if($blotter->hearing_datetime)
        <div class="mb-4">
            <div class="border rounded p-3 bg-danger bg-opacity-10">

                <h6 class="text-warning-emphasis fw-bold">
                    <i class="fa-solid fa-calendar-days"></i> Scheduled Hearing
                </h6>

                <div class="row mt-3">

                    <div class="col-md-4">
                        <small class="text-muted">Date</small>
                        <div class="fw-semibold">{{ $blotter->hearing_datetime->format('Y-m-d') }}</div>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted">Time</small>
                        <div class="fw-semibold">{{ $blotter->hearing_datetime->format('H:i') }}</div>
                    </div>

                    <div class="col-md-4">
                        <small class="text-muted">Venue</small>
                        <div class="fw-semibold">{{ $blotter->hearing_venue }}</div>
                    </div>

                </div>

            </div>
        </div>
        @endif

        <hr>

        <!-- STATUS -->
        <div class="mb-3">

            <h6 class="fw-bold">Update Case Status</h6>

            <div class="d-flex flex-wrap gap-2">
                <form method="POST" action="{{ route('committee.peace.blotter.update-status', $blotter->id) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Filed">
                    <button type="submit" class="btn {{ $blotter->status == 'Filed' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm">
                        Filed
                    </button>
                </form>

                <form method="POST" action="{{ route('committee.peace.blotter.update-status', $blotter->id) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Mediation">
                    <button type="submit" class="btn {{ $blotter->status == 'Mediation' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm">
                        Mediation
                    </button>
                </form>

                <form method="POST" action="{{ route('committee.peace.blotter.update-status', $blotter->id) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Hearing">
                    <button type="submit" class="btn {{ $blotter->status == 'Hearing' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm">
                        Hearing
                    </button>
                </form>

                <form method="POST" action="{{ route('committee.peace.blotter.update-status', $blotter->id) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Settled">
                    <button type="submit" class="btn {{ $blotter->status == 'Settled' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm">
                        Settled
                    </button>
                </form>

                <form method="POST" action="{{ route('committee.peace.blotter.update-status', $blotter->id) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Unresolved">
                    <button type="submit" class="btn {{ $blotter->status == 'Unresolved' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm">
                        Unresolved
                    </button>
                </form>

                <form method="POST" action="{{ route('committee.peace.blotter.update-status', $blotter->id) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Endorsed">
                    <button type="submit" class="btn {{ $blotter->status == 'Endorsed' ? 'btn-primary' : 'btn-outline-secondary' }} btn-sm">
                        Endorsed
                    </button>
                </form>
            </div>

        </div>

        <!-- ACTION BUTTONS -->
        <div class="d-flex flex-wrap gap-3 mt-3">

            <form method="POST" target="_blank" action="{{ route('committee.peace.blotter.generateHearing', $blotter->id) }}" class="d-inline">
                @csrf

                <button class="btn btn-success">
                    <i class="fa-regular fa-file"></i> Generate Notice
                </button>
            </form>



            <button class="btn btn-primary">
                <i class="fa-regular fa-file-lines"></i> Print Certificate of Settlement
            </button>

            <button class="btn btn-warning text-white">
                <i class="fa-solid fa-triangle-exclamation"></i> Endorse to Court
            </button>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="documentsModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Uploaded Documents</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fa-solid fa-paperclip"></i> Documents
                            <span class="badge bg-secondary ms-2">{{ $blotter->documents_count }}</span>
                        </h6>

                        @if($blotter->documents_count === 0)
                            <div class="text-muted">No attachments uploaded.</div>
                        @else
                            @foreach($documentsByType as $type => $documents)
                                <div class="mb-4">
                                    <h6 class="border-bottom pb-2 mb-2">
                                        {{ $type }}
                                        <span class="badge bg-secondary ms-2">{{ $documents->count() }}</span>
                                    </h6>

                                    <div class="list-group">
                                        @foreach($documents as $document)
                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                <div class="d-flex align-items-center">
                                                    <i class="fa-regular {{ $document->file_icon }} fa-lg me-3"></i>
                                                    <div>
                                                        <div class="fw-semibold">{{ $document->file_name }}</div>
                                                        <small class="text-muted">
                                                            {{ $document->formatted_size }} •
                                                            Uploaded by {{ $document->uploaded_by ?? 'System' }} •
                                                            {{ $document->created_at?->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="btn-group">
                                                    <a href="{{ $document->file_url }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        <i class="fa-regular fa-eye"></i>
                                                    </a>
                                                    <a href="{{ $document->file_url }}" class="btn btn-sm btn-outline-success" download="{{ $document->file_name }}">
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection
