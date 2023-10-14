@php
    $menus = [
        [ "url" => "dashboard.index", "label" => "Dashboard", "icon" => "", "roles" => ["ADMIN", "USER"] ],
        [ "url" => "waste.index", "label" => "Waste", "icon" => "", "roles" => ["ADMIN"] ],
        [ "url" => "transaction.index", "label" => "Transaction", "icon" => "", "roles" => ["USER"] ],
    ];
@endphp

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="index.html">Test Fanatech</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.html">TF</a>
        </div>
        <ul class="sidebar-menu">
            @foreach ($menus as $menu)
                @role($menu['roles'])
                    <li class="{{ Route::currentRouteName() == $menu['url'] ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route($menu['url']) }}"><i class="far fa-square"></i> <span>{{ $menu['label'] }}</span></a>
                    </li>
                @endrole
            @endforeach
        </ul>
    </aside>
</div>
