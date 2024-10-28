<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile dropdown no-arrow">
            <a href="#" class="nav-link d-flex align-items-center" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="nav-profile-image">
                    {{-- <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="profile" class="img-profile rounded-circle" /> --}}
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">
                        {{-- {{ Auth::user()->fullname ?? Auth::user()->email ?? Auth::user()->username }} --}}
                    </span>
                    @if(Auth::user()->role == 2)
                        <span>Admin</span>
                    @elseif(Auth::user()->role == 1)
                        <span>Manager</span>
                    @endif
                </div>
                <!-- Dropdown toggle icon -->
                <span class="mdi mdi-dots-vertical mdi-24px ms-3"></span>
            </a>
            <!-- Dropdown menu -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item bg-red text-center" href="{{route('admin.edit')}}">
                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                    Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item bg-yellow" href="#" data-toggle="modal" data-target="#logoutModal">
                    <form action="{{ route('logout') }}" method="POST" class=" text-center">
                        @csrf
                        <button type="submit" class="btn badge bg-danger" onclick="return confirm('chắc chắn đằng xuất')">Log Out</button>
                    </form>
                </a>
            </div>
        </li>

        <li class="nav-item">

            <a class="nav-link" href="{{route('admin.dashboard')}}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('products.index') }}">
                <span class="menu-title">Products</span>
                <i class="mdi mdi-tshirt-crew menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('categories.index')}}">
                <span class="menu-title">Category</span>
                <i class="mdi mdi-format-list-bulleted menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('orders.index')}}">
                <span class="menu-title">Order</span>
                <i class="mdi mdi-clipboard menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('promotion.index')}}">
                <span class="menu-title">Promotion</span>
                <i class="mdi mdi-clipboard menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('managers.index') }}">
                <span class="menu-title">User</span>
                <i class="mdi mdi-account menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#tables">
                <span class="menu-title">Comment</span>
                <i class="mdi mdi-comment menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('sizes.index')}}">
                <span class="menu-title">Size</span>
                <i class="mdi mdi-format-size menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('colors.index')}}">
                <span class="menu-title">Color</span>
                <i class="mdi mdi-format-color-fill menu-icon"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('vouchers.index')}}">
                <span class="menu-title">Voucher</span>
                <i class="mdi mdi-format-color-fill menu-icon"></i>
            </a>
        </li>
    </ul>
</nav>
