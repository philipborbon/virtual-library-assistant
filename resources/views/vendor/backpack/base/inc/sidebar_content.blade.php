{{-- This file is used to store sidebar items, inside the Backpack admin panel --}}
{{-- <li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li> --}}

<li class="nav-item"><a class="nav-link" href="{{ backpack_url('pending') }}"><i class="nav-icon la la-home"></i> Pendings</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon la la-user"></i> Users</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('category') }}"><i class="nav-icon la la-list"></i> Categories</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('book') }}"><i class="nav-icon la la-folder-open"></i> Books</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('history') }}"><i class="nav-icon la la-archive"></i> History</a></li>