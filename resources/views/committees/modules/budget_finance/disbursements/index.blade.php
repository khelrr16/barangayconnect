@extends('layouts.committee')

@section('title', 'Disbursements')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Disbursements</h4>
            <a href="{{ route('committee.disbursements.create') }}" class="btn btn-primary">+ New Disbursement</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if($disbursements->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($disbursements as $d)
                                    <tr>
                                        <td>{{ $d->date->format('M d, Y') }}</td>
                                        <td>{{ $d->description ?: '—' }}</td>
                                        <td>{{ $d->category ?: '—' }}</td>
                                        <td class="text-end fw-semibold">&#8369; {{ number_format($d->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $disbursements->links() }}
                    </div>
                @else
                    <p class="text-muted mb-0">No disbursements recorded yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
