@extends('layouts.committee')

@section('title', 'Financial Reports')

@section('content')
    <div class="container-fluid mt-4">
        <h4 class="fw-bold mb-4">Financial Reports</h4>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('committee.reports.index') }}" class="row g-2 align-items-end">
                    <div class="col-auto">
                        <label for="from" class="form-label mb-0">From</label>
                        <input type="date" class="form-control" id="from" name="from" value="{{ $from }}">
                    </div>
                    <div class="col-auto">
                        <label for="to" class="form-label mb-0">To</label>
                        <input type="date" class="form-control" id="to" name="to" value="{{ $to }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('committee.reports.index') }}" class="btn btn-outline-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>

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
                    <div class="card-header bg-light"><strong>Disbursements</strong></div>
                    <div class="card-body">
                        @forelse($disbursements as $d)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>{{ $d->date->format('M d, Y') }} - {{ $d->description ?: '—' }}</span>
                                <span class="fw-semibold">&#8369; {{ number_format($d->amount, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No disbursements in this period.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light"><strong>Fund Sources</strong></div>
                    <div class="card-body">
                        @forelse($fundSources as $f)
                            <div class="d-flex justify-content-between py-2 border-bottom">
                                <span>{{ $f->name }} @if($f->date) ({{ $f->date->format('M d, Y') }}) @endif</span>
                                <span class="fw-semibold text-success">&#8369; {{ number_format($f->amount, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No fund sources in this period.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
