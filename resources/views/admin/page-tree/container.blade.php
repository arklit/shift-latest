<ul class="main-list">
    @if(!empty($pages))
        @forelse($pages as $page)
            <li class="has-children parent">
                <div class="label" @if(!isset($page['children'])) style="padding-left: 18px" @endif>
                    @if(isset($page['children']))
                        <span class="closed-img"></span>
                    @endif
                    <img class="type-img" src="/assets/img/admin/tree/page.svg" alt="">
                    <a href="{{ route('platform.pages.edit', [$page['id']]) }}"
                       class="page-name">{{ $page['name'] }}</a>
                    <span class="uri">{{ $page['uri'] }}</span>
                </div>
                @if(isset($page['children']))
                    @include('admin.page-tree.list', ['children' => $page['children']])
                @endif
            </li>
        @empty
        @endforelse
    @endif
</ul>
