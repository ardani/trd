<nav class="side-menu">
    <ul class="side-menu-list">
        <li class="grey">
            <a href="{{url('/')}}">
                <span>
                    <i class="font-icon font-icon-speed"></i>
                    <span class="lbl">Dashboard</span>
                </span>
            </a>
        </li>
        @foreach($menus as $menu)
            <li class="{{$menu['parent']->class}} {{in_array(request()->path(),$menu['groups']) ? 'opened' : ''}}">
            <span>
                <i class="{{$menu['parent']->icon}}"></i>
                <span class="lbl">{{$menu['parent']->name}}</span>
            </span>
                <ul>
                    @foreach($menu['childs'] as $child)
                        <li><a href="{{url($child->path)}}"><span class="lbl">{{$child->name}}</span></a></li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>
</nav>
