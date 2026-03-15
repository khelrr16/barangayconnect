@extends('layouts.committee')

@section('title', 'Budget Overview')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold mb-4">Budget Overview</h4>

        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Fund Sources</small>
                        <h4 class="fw-bold text-success">&#8369; {{ number_format($totalFundSources, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Total Disbursements</small>
                        <h4 class="fw-bold text-danger">&#8369; {{ number_format($totalDisbursements, 2) }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <small class="text-muted">Balance</small>
                        <h4 class="fw-bold">&#8369; {{ number_format($balance, 2) }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <strong>Recent Disbursements</strong>
                        <a href="{{ route('committee.disbursements.index') }}" class="btn btn-sm btn-outline-primary float-end">View all</a>
                    </div>
                    <div class="card-body">
                        @forelse($recentDisbursements as $d)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>{{ $d->date->format('M d, Y') }} - {{ $d->description ?: '—' }}</span>
                                <span class="fw-semibold">&#8369; {{ number_format($d->amount, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No disbursements yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <strong>Recent Fund Sources</strong>
                        <a href="{{ route('committee.fund-sources.index') }}" class="btn btn-sm btn-outline-primary float-end">View all</a>
                    </div>
                    <div class="card-body">
                        @forelse($recentFundSources as $f)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>{{ $f->name }} @if($f->date) ({{ $f->date->format('M d, Y') }}) @endif</span>
                                <span class="fw-semibold text-success">&#8369; {{ number_format($f->amount, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No fund sources yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
