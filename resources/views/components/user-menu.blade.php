<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
          with font-awesome or any other icon font library -->
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->is('home') ? 'active' : '' }}">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                    Dashboard
                </p>
            </a>
        </li>
        @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) || Auth::user()->campaign_id == 4)
            <li class="nav-header">VOICE AUDITS</li>
            <li class="nav-item">
                <a href="{{ route('voice-audits.qa-pending-sale-sheet', 1) }}"
                   class="nav-link {{ request()->is('qa-pending-sale-sheet/1', 'qa-pending-sale-sheet/1/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clock"></i>
                    <p>
                        Pending Audits
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('voice-audits.index', 1) }}"
                   class="nav-link {{ request()->is('voice-audits/1', 'voice-audits/1/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-balance-scale"></i>
                    <p>
                        All Audits
                    </p>
                </a>
            </li>
            @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) ||
                    (in_array(Auth::user()->roles[0]->name, ['Super Admin', 'Director', 'Manager', 'Team Lead']) &&
                        Auth::user()->campaign_id == 4))
                <li
                    class="nav-item {{ request()->is('compliance-voice-audits/1', 'compliance-voice-audits/1/*') ? 'menu-open' : '' }}">
                    <a href="#"
                       class="nav-link {{ request()->is('compliance-voice-audits/1', 'compliance-voice-audits/1/*', 'compliance-voice-audits/show-all-compliance-voice-audits/1', 'compliance-voice-audits/show-all-compliance-voice-audits/1/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Compliance Audits
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('compliance-voice-audits.index', 1) }}"
                               class="nav-link {{ request()->is('compliance-voice-audits/1', 'compliance-voice-audits/1/*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Assigned Audits</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('compliance-voice-audits.show-all', 1) }}"
                               class="nav-link {{ request()->is('compliance-voice-audits/show-all-compliance-voice-audits/1', 'compliance-voice-audits/show-all-compliance-voice-audits/1/*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Audits</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('voice-audit-appeals.index') }}"
                       class="nav-link {{ request()->is('voice-audit-appeals', 'voice-audit-appeals/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bahai"></i>
                        <p>
                            Audit Appeals
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('voice-audit-actions.index') }}"
                       class="nav-link {{ request()->is('voice-audit-actions', 'voice-audit-actions/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>
                            Audit Actions
                        </p>
                    </a>
                </li>
            @endif

        @endif

        @if (in_array(Auth::user()->roles[0]->name, ['Super Admin', 'Director', 'Team Lead', 'Manager', 'Associate']) &&
                (Auth::user()->campaign_id != 4 && Auth::user()->campaign_id != 98))

            @if (Auth::user()->roles[0]->name == 'Associate')
                <li class="nav-item">
                    <a href="{{ route('voice-evaluation-reviews.my-reviews') }}"
                       class="nav-link {{ request()->is('my-voice-reviews', 'my-voice-reviews/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-angry"></i>
                        <p>
                            My Evaluation Reviews
                        </p>
                    </a>
                </li>
            @elseif(in_array(Auth::user()->roles[0]->name, ['Director', 'Team Lead', 'Manager']) && Auth::user()->campaign_id != 4)
                <li class="nav-item">
                    <a href="{{ route('voice-evaluation-reviews.index', 'pending') }}"
                       class="nav-link {{ request()->is('voice-evaluation-reviews/pending') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-balance-scale-right"></i>
                        <p>
                            Pending Reviews
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('voice-evaluation-reviews.index', 'all') }}"
                       class="nav-link {{ request()->is('voice-evaluation-reviews/all') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-headphones"></i>
                        <p>
                            Evaluations List
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('voice-evaluation-reviews.my-appeals') }}"
                       class="nav-link {{ request()->is('my-voice-appeals', 'my-voice-appeals/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bahai"></i>
                        <p>
                            Appeals List
                        </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('voice-evaluation-reviews.my-actions') }}"
                       class="nav-link {{ request()->is('my-voice-actions', 'my-voice-actions/*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>
                            Actions List
                        </p>
                    </a>
                </li>
            @endif
        @endif

        @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) ||
                (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead']) &&
                    (Auth::user()->campaign_id == 4)))
            <li class="nav-header">VOICE REPORTS</li>

            <li class="nav-item">
                <a href="{{ route('voice-reports.timesheet') }}?search=1&search_id=-1&from_date={{ now()->startOfMonth() }}&to_date={{ now()->endOfMonth() }}"
                   class="nav-link {{ request()->is('voice-reports/timesheet', 'voice-reports/timesheet/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-clock"></i>
                    <p>Timesheet Report</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('voice-reports.evaluators') }}?search=1&search_id=-1&from_date={{ now()->startOfMonth() }}&to_date={{ now()->endOfMonth() }}"
                   class="nav-link {{ request()->is('voice-reports/evaluators', 'voice-reports/evaluators/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-address-book"></i>
                    <p>Evaluators Report</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('voice-reports.campaigns') }}?search=1&campaign_id=-1&from_date={{ now()->startOfMonth() }}&to_date={{ now()->endOfMonth() }}"
                   class="nav-link {{ request()->is('voice-reports/campaigns', 'voice-reports/campaigns/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-book"></i>
                    <p>Campaigns Report</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('voice-reports.team-leads') }}?search=1&search_id=-1&from_date={{ now()->startOfMonth() }}&to_date={{ now()->endOfMonth() }}"
                   class="nav-link {{ request()->is('voice-reports/team-leads', 'voice-reports/team-leads/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-user-md"></i>
                    <p>Team Leads Report</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('voice-reports.associates') }}?search=1&search_id=-1&from_date={{ now()->startOfMonth() }}&to_date={{ now()->endOfMonth() }}"
                   class="nav-link {{ request()->is('voice-reports/associates', 'voice-reports/associates/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Associates Report</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('voice-reports.fatals') }}?search=1&search_id=-1&from_date={{ now()->startOfMonth() }}&to_date={{ now()->endOfMonth() }}"
                   class="nav-link {{ request()->is('voice-reports/fatals', 'voice-reports/fatals/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-ban"></i>
                    <p>Fatal Report</p>
                </a>
            </li>
        @endif
        {{-- @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']) || (in_array(Auth::user()->roles[0]->name, ['Director', 'Manager', 'Team Lead']) && Auth::user()->campaign_id == 4))
            <li class="nav-header">SOLAR LT</li>

            <li class="nav-item">
                <a href="{{ route('solar-lts.audit.index') }}"
                    class="nav-link {{ request()->is('solar-lts/audit') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-solar-panel"></i>
                    <p>Solar LT Audit</p>
                </a>
            </li>

            <li class="nav-header">SOLAR LT REPORTS</li>
            <li class="nav-item">
                <a href="{{ route('solar-lts.audit.report') }}"
                    class="nav-link {{ request()->is('solar-lts/audit/audit-report') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-microphone"></i>
                    <p>Solar LT Report</p>
                </a>
            </li>
        @endif --}}

        @if (in_array(Auth::user()->roles[0]->name, ['Super Admin']))

            <li class="nav-header">SETTINGS</li>

            <li class="nav-item">
                <a href="{{ route('solar-lts.voice-evaluations.index') }}"
                   class="nav-link {{ request()->is('solar-lts/voice-evaluations') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-question"></i>
                    <p>Solar LT Evaluations</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('voice-evaluations.index') }}"
                   class="nav-link {{ request()->is('voice-evaluations', 'voice-evaluations/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-microphone"></i>
                    <p>Voice Evaluations</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('voice-evaluation-actions.index') }}"
                   class="nav-link {{ request()->is('voice-evaluation-actions', 'voice-evaluation-actions/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-atom"></i>
                    <p>Voice Evaluation Action</p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('campaigns.index') }}"
                   class="nav-link {{ request()->is('campaigns', 'campaigns/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-boxes"></i>
                    <p>Campaigns</p>
                </a>
            </li>

            <li class="nav-item {{ request()->is('users', 'users/*', 'roles', 'roles/*') ? 'menu-open' : '' }}">
                <a href="#"
                   class="nav-link {{ request()->is('users', 'users/*', 'roles', 'roles/*') ? 'active' : '' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>
                        Users
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                           class="nav-link {{ request()->is('users', 'users/*') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>User</p>
                        </a>
                    </li>

                    @if (Auth::user()->roles[0]->name == 'Super Admin')
                        <li class="nav-item">
                            <a href="{{ route('roles.index') }}"
                               class="nav-link {{ request()->is('roles', 'roles/*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User Roles</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>

        @endif

    </ul>
</nav>
