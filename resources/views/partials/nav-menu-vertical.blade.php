<ul class="list-group list-unstyled pz-u-list">
    @foreach($navRoutes as $menuName=>$routeAlias)
        <li class="list-group-item pz-menu-list-item">
            <a href="{{ route($routeAlias, []) }}" class="pz-menu-link">{{ $menuName }}</a>
        </li>
    @endforeach
</ul>
