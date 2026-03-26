<aside class="sidebar" id="sidebar">
    @php
        $user = auth()->user();
        $isAdmin = $user && $user->hasRole('admin');
        $isAssistant = $user && $user->hasRole('assistant') && $user->committeeAssistantAccessAsAssistant()->exists();

        $activeCommitteeSlug = null;
        $activeCommitteeName = null;

        if ($isAdmin) {
            $activeCommitteeSlug = session('admin_active_committee_slug');
            $activeCommitteeName = session('admin_active_committee_name');
        } elseif ($isAssistant) {
            $activeCommitteeSlug = $user->assistedCommitteeSlug();
        } elseif ($user && $user->official && $user->official->committee) {
            $activeCommitteeSlug = $user->official->committee->slug;
            $activeCommitteeName = $user->official->committee->name;
        }
    @endphp

    <div class="sidebar-header">
        <div class="sidebar-logo">BarangayConnect</div>
        @if($user && $user->official_id)
            <div class="text-center">{{ $user->official->position }}</div>
            <div class="text-center">{{ $user->official->committee->name }}</div>
        @elseif($isAdmin && $activeCommitteeName)
            <div class="text-center">ADMIN VIEW</div>
            <div class="text-center">{{ $activeCommitteeName }}</div>
        @endif


    </div>

    <nav class="sidebar-nav">
        <ul class="nav-menu">
            @if(auth()->check() && ($user->can('view committee_dashboard') || $isAssistant || $isAdmin))
            <li class="nav-item">
                <a href="{{ route('committee.index') }}" class="nav-link">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            @endif

            @if(auth()->check() && $user->isCommitteeHeadLike() && $user->official && $user->official->committee)
                <li class="nav-item">
                    <a href="{{ route('committee.permissions.index') }}" class="nav-link">
                        <i class="fa-solid fa-user-shield"></i>
                        <span class="nav-text">Permissions</span>
                    </a>
                </li>
            @endif

            @if($isAssistant)
                @if($activeCommitteeSlug === 'health_sanitation')
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
                @elseif($activeCommitteeSlug === 'peace_order')
                    <li class="nav-item">
                        <a href="{{ route('committee.peace.blotter.index') }}" class="nav-link">
                            <i class="fa-solid fa-gavel"></i>
                            <span class="nav-text">Blotter</span>
                        </a>
                    </li>
                @elseif($activeCommitteeSlug === 'budget_finance')
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
            @elseif($isAdmin)
                @if($activeCommitteeSlug === 'health_sanitation')
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
                @elseif($activeCommitteeSlug === 'peace_order')
                    <li class="nav-item">
                        <a href="{{ route('committee.peace.blotter.index') }}" class="nav-link">
                            <i class="fa-solid fa-gavel"></i>
                            <span class="nav-text">Blotter</span>
                        </a>
                    </li>
                @elseif($activeCommitteeSlug === 'budget_finance')
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

            @if($isAdmin)
                <li class="nav-item mt-2">
                    <a href="{{ route('admin.index') }}" class="nav-link">
                        <i class="fa-solid fa-arrow-left"></i>
                        <span class="nav-text">Back to Admin Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.committee.index') }}" class="nav-link">
                        <i class="fa-solid fa-list"></i>
                        <span class="nav-text">Back to Committees</span>
                    </a>
                </li>
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
