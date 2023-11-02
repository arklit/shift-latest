<div class="bg-white rounded shadow-sm mb-3 page-tree-container">
    <div class="command-bar">
        <div class="search-bar">
            <input type="text" placeholder="Поиск">
        </div>
        <button type="button" class="uncollapse-all">Раскрыть всё</button>
        <button type="button" class="collapse-all">Скрыть всё</button>
    </div>

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
                    @include('admin.page-tree-element', ['children' => $page->children])
                </li>
            @empty
            @endforelse
        @endif
    </ul>
</div>

