<ul class="list">
    @if(!empty($children))
        @foreach($children as $child)
            <li @if(isset($child['children'])) class="has-children" @endif>
                <div class="label" @if(empty($child['children'])) style="padding-left: 18px" @endif>
                    @if(isset($child['children']) && !empty($child['children']))
                        <span class="closed-img"></span>
                    @endif
                    <img class="type-img" src="/assets/img/admin/tree/template.svg" alt="">
                    <span class="page-name">{{$child['name']}}</span> <span href="{{ route('platform.pages.edit', [$child['id']]) }}" class="uri">{{$child['uri']}}</span>
                </div>
                @if(isset($child['children']))
                    @include('admin.page-tree.list',['children' => $child['children']])
                @endif
            </li>
        @endforeach
    @endif
</ul>
