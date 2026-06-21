<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        {{-- <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="ExpenseTracker Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
        <span class="brand-text font-weight-light">Inventory Management Pro</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                @if (auth()->user()->image)
                    <img src="{{ asset('uploads/avatars/' . auth()->user()->image) }}" class="img-circle elevation-2"
                        alt="User Image">
                @else
                    <img src="{{ asset('dist/img/avatar.png') }}" class="img-circle elevation-2" alt="User Image">
                @endif
            </div>
            <div class="info">
                <a href="{{ route('profile.index') }}" class="d-block">
                    {{ auth()->user()->name }}
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">

                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- MASTER DATA --}}
                <li class="nav-header">MASTER DATA</li>

                {{-- Categories --}}
                <li class="nav-item">
                    <a href="{{ route('categories.index') }}"
                        class="nav-link {{ Request::is('categories*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Categories</p>
                    </a>
                </li>

                {{-- Units --}}
                <li class="nav-item">
                    <a href="{{ route('units.index') }}" class="nav-link {{ Request::is('units*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-balance-scale"></i>
                        <p>Units</p>
                    </a>
                </li>

                {{-- Suppliers --}}
                <li class="nav-item">
                    <a href="{{ route('suppliers.index') }}"
                        class="nav-link {{ Request::is('suppliers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-truck"></i>
                        <p>Suppliers</p>
                    </a>
                </li>

                {{-- Products --}}
                <li class="nav-item">
                    <a href="{{ route('products.index') }}"
                        class="nav-link {{ Request::is('products*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Products</p>
                    </a>
                </li>

                {{-- Customers --}}
                <li class="nav-item">
                    <a href="{{ route('customers.index') }}"
                        class="nav-link {{ Request::is('customers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Customers</p>
                    </a>
                </li>



                {{-- TRANSACTIONS --}}
                <li class="nav-header">TRANSACTIONS</li>

                {{-- Purchases --}}
                <li class="nav-item">
                    <a href="{{ route('purchases.index') }}"
                        class="nav-link {{ Request::is('purchases*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>Purchases</p>
                    </a>
                </li>

                {{-- Sales --}}
                <li class="nav-item">
                    <a href="{{ route('sales.index') }}" class="nav-link {{ Request::is('sales*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cash-register"></i>
                        <p>Sales</p>
                    </a>
                </li>

                {{-- REPORTS --}}
                <li class="nav-header">REPORTS</li>

                <li class="nav-item">
                    <a href="{{ route('reports.stock') }}"
                        class="nav-link {{ Request::is('reports/stock') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Stock Report</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('reports.purchase') }}"
                        class="nav-link {{ Request::is('reports/purchase') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Purchase Report</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('reports.sales') }}"
                        class="nav-link {{ Request::is('reports/sales') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Sales Report</p>
                    </a>
                </li>

                {{-- ACCOUNT --}}
                <li class="nav-header">ACCOUNT</li>

                <li class="nav-item">
                    <a href="{{ route('profile.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-circle"></i>
                        <p>Profile</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                        class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                        @csrf
                    </form>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
