<aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

        @can('is_admin')
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-grid"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.users.index') }}">
                    <i class="bi bi-person"></i>
                    <span>Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.exams.index') }}">
                    <i class="bi bi-question-circle"></i>
                    <span>Exams</span>
                </a>
            </li>
        @endcan

        @if (Gate::allows('is_teacher') || Gate::allows('is_admin'))
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.exams.register_index') }}">
                    <i class="bi bi-question-circle"></i>
                    <span>Register Exams</span>
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('admin.reporting.index') }}">
                <i class="bi bi-receipt-cutoff"></i>
                <span>Reporting</span>
            </a>
        </li>
        <!-- <li class="nav-item">
                            <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#" aria-expanded="false"> <i class="bi bi-layout-text-window-reverse"></i><span>Settings</span><i class="bi bi-chevron-down ms-auto"></i> </a>
                            <ul id="tables-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav" style="">
                                <li><a href="tables-general.html"> <i class="bi bi-circle"></i><span>Questions</span> </a></li>
                                <li><a href="tables-data.html"> <i class="bi bi-circle"></i><span>Options</span> </a></li>
                            </ul>
                        </li> -->

    </ul>

</aside>
