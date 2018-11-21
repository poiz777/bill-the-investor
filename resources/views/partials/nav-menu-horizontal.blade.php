<ul class="list-group list-unstyled pz-u-list col-md-12 no-lr-pad">
    @foreach($navRoutes as $menuName=>$routeAlias)
        <li class="list-group-item pz-menu-list-item-horizontal col-md-4">
            <a href="{{ route($routeAlias, []) }}" class="pz-menu-link">{{ $menuName }}</a>
        </li>
    @endforeach
</ul>
