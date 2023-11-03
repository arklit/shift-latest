<ul class="list">
    @if($children->isNotEmpty())
        @foreach($children as $child)
            <li @if($child->children->isNotEmpty()) class="has-children" @endif>
                <div class="label" @if($child->children->isEmpty()) style="padding-left: 18px" @endif>
                    @if($child->children->isNotEmpty())
                        <span class="closed-img"></span>
                    @endif
                    <img class="type-img" src="/assets/img/admin/tree/{{$child->getPageType()}}.svg" alt="">
                    <span class="page-name">{{$child->name}}</span> <span href="{{ route('platform.pages.edit', [$child->id]) }}" class="uri">{{$child->uri}}</span>
                </div>
                @if($child->children->isNotEmpty())
                    @include('admin.page-tree.list',['children' => $child->children])
                @endif
            </li>
        @endforeach
    @endif
</ul>
