<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item">
            <a class="nav-link" href="index.html">
                <i class="icon-grid menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        @if (session('role_id') !== 3)
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#master-submenu" aria-expanded="false"
                    aria-controls="master-submenu">
                    <i class="ti-server menu-icon"></i>
                    <span class="menu-title">Data Master</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="master-submenu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('admin.users.index') }}">Data User</a>
                        </li>

                        @if (session('role_id') == 1)
                            <li class="nav-item"> <a class="nav-link"
                                    href="{{ route('admin.mapping-user.index') }}">Mapping
                                    User</a></li>
                            <li class="nav-item"> <a class="nav-link"
                                    href="{{ route('admin.master-ticket.index') }}">Master
                                    Ticket</a></li>
                            <li class="nav-item"> <a class="nav-link"
                                    href="{{ route('admin.destinations.index') }}">Master
                                    Destination</a></li>
                            <li class="nav-item"> <a class="nav-link"
                                    href="{{ route('admin.bank-accounts.index') }}">Bank Accounts</a></li>
                        @endif

                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false"
                    aria-controls="ui-basic">
                    <i class="ti-wallet menu-icon"></i>
                    <span class="menu-title">Transaction</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="pages/ui-features/buttons.html">On The Spot</a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="pages/ui-features/dropdowns.html">Online</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="collapse" href="#setting-dropdown" aria-expanded="false"
                    aria-controls="setting-dropdown">
                    <i class="ti-settings menu-icon"></i>
                    <span class="menu-title">Settings</span>
                    <i class="menu-arrow"></i>
                </a>
                <div class="collapse" id="setting-dropdown">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="{{ route('admin.payment-option.index') }}">Payment Option</a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        <li class="nav-item">
            <form method="POST" action="{{ route('logout.process') }}">
                @csrf
                <button type="submit" class="nav-link border-0 bg-transparent">
                    <i class="ti-power-off menu-icon"></i>
                    <span class="menu-title">Logout</span>
                </button>
            </form>
        </li>

    </ul>
</nav>
