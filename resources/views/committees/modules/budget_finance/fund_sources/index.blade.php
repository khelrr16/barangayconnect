@extends('layouts.committee')

@section('title', 'Fund Sources')

@section('content')
    <div class="container-fluid mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Fund Sources</h4>
            <a href="{{ route('committee.fund-sources.create') }}" class="btn btn-primary">+ New Fund Source</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                @if($fundSources->isNotEmpty())
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th class="text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fundSources as $f)
                                    <tr>
                                        <td>{{ $f->name }}</td>
                                        <td>{{ $f->type ?: '—' }}</td>
                                        <td>{{ $f->date ? $f->date->format('M d, Y') : '—' }}</td>
                                        <td class="text-end fw-semibold text-success">&#8369; {{ number_format($f->amount, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $fundSources->links() }}
                    </div>
                @else
                    <p class="text-muted mb-0">No fund sources recorded yet.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
