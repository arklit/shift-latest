<ul class="main-list">
    @if($pages->isNotEmpty())
    @forelse($pages as $page)
    <li class="has-children parent">
        <div class="label" @if($page->children->isEmpty()) style="padding-left: 18px" @endif>
            @if($page->children->isNotEmpty())
            <span class="closed-img"></span>
            @endif
            <img class="type-img" src="/assets/img/admin/tree/{{$page->getPageType()}}.svg" alt="">
            <a href="{{ route('platform.pages.edit', [$page->id]) }}" class="page-name">{{ $page->name }}</a>
            <span class="uri">{{ $page->uri }}</span>
        </div>
        @include('admin.page-tree.list', ['children' => $page->children])
    </li>
    @empty
    @endforelse
    @endif
</ul>
