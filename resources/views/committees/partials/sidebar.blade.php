<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">BarangayConnect</div>
        <div class="text-center">Committee Member</div>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-menu">
            @if(auth()->check() && (auth()->user()->can('view committee_dashboard') || (auth()->user()->hasRole('assistant') && auth()->user()->committeeAssistantAccessAsAssistant()->exists())))
            <li class="nav-item">
                <a href="{{ route('committee.index') }}" class="nav-link">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            @endif

            @if(auth()->check() && auth()->user()->isCommitteeHeadLike() && auth()->user()->official && auth()->user()->official->committee)
                <li class="nav-item">
                    <a href="{{ route('committee.permissions.index') }}" class="nav-link">
                        <i class="fa-solid fa-user-shield"></i>
                        <span class="nav-text">Permissions</span>
                    </a>
                </li>
            @endif

            @php
                $isAssistant = auth()->check() && auth()->user()->hasRole('assistant') && auth()->user()->committeeAssistantAccessAsAssistant()->exists();
                $assistantCommitteeSlug = $isAssistant ? auth()->user()->assistedCommitteeSlug() : null;
            @endphp

            @if($isAssistant)
                @if($assistantCommitteeSlug === 'health_sanitation')
                    <li class="nav-item">
                        <a href="{{ route('committee.health.immunization.index') }}" class="nav-link">
                            <i class="fa-solid fa-syringe"></i>
                            <span class="nav-text">Immunization for Infants</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('committee.health.medicine.index') }}" class="nav-link">
                            <i class="fa-solid fa-tablets"></i>
                            <span class="nav-text">Medicine Inventory</span>
                        </a>
                    </li>
                @elseif($assistantCommitteeSlug === 'peace_order')
                    <li class="nav-item">
                        <a href="{{ route('committee.peace.blotter.index') }}" class="nav-link">
                            <i class="fa-solid fa-gavel"></i>
                            <span class="nav-text">Blotter</span>
                        </a>
                    </li>
                @elseif($assistantCommitteeSlug === 'budget_finance')
                    <li class="nav-item">
                        <a href="{{ route('committee.budget.index') }}" class="nav-link">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span class="nav-text">Budget Overview</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('committee.disbursements.index') }}" class="nav-link">
                            <i class="fa-solid fa-money-bill-transfer"></i>
                            <span class="nav-text">Disbursements</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('committee.fund-sources.index') }}" class="nav-link">
                            <i class="fa-solid fa-piggy-bank"></i>
                            <span class="nav-text">Fund Sources</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('committee.reports.index') }}" class="nav-link">
                            <i class="fa-solid fa-file-lines"></i>
                            <span class="nav-text">Financial Reports</span>
                        </a>
                    </li>
                @endif
            @else
            @can('view health_sanitation')
                <li class="nav-item">
                    <a href="{{ route('committee.health.immunization.index') }}" class="nav-link">
                        <i class="fa-solid fa-syringe"></i>
                        <span class="nav-text">Immunization for Infants</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('committee.health.medicine.index') }}" class="nav-link">
                        <i class="fa-solid fa-tablets"></i>
                        <span class="nav-text">Medicine Inventory</span>
                    </a>
                </li>
            @endcan

            @can('view peace_order')
                <li class="nav-item">
                    <a href="{{ route('committee.peace.blotter.index') }}" class="nav-link">
                        <i class="fa-solid fa-gavel"></i>
                        <span class="nav-text">Blotter</span>
                    </a>
                </li>
            @endcan

            @can('view budget_finance')
                <li class="nav-item">
                    <a href="{{ route('committee.budget.index') }}" class="nav-link">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span class="nav-text">Budget Overview</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('committee.disbursements.index') }}" class="nav-link">
                        <i class="fa-solid fa-money-bill-transfer"></i>
                        <span class="nav-text">Disbursements</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('committee.fund-sources.index') }}" class="nav-link">
                        <i class="fa-solid fa-piggy-bank"></i>
                        <span class="nav-text">Fund Sources</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('committee.reports.index') }}" class="nav-link">
                        <i class="fa-solid fa-file-lines"></i>
                        <span class="nav-text">Financial Reports</span>
                    </a>
                </li>
            @endcan
            @endif
        </ul>
    </nav>

    <nav class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="logout-btn btn btn-danger" type="submit">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        </form>
    </nav>
</aside>
