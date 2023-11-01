<ul class="list">
    @if(!empty($children))
        @foreach($children as $child)
            <li @if(!empty($child->children)) class="has-children" @endif>
                <div class="label" href="">{{$child->name}}</div>
                @if(!empty($child->children))
                    @include('admin.page-tree-element',['children' => $child->children])
                @endif
            </li>
        @endforeach
    @endif
</ul>
