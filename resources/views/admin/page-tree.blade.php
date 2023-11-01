<div class="bg-white rounded shadow-sm mb-3 page-tree-container">
    <ul class="main-list">
        @if(!empty($pages))
            @forelse($pages as $page)
                <li class="has-children">
                    <div class="label">
                        {{ $page->name }}
                    </div>
                    @include('admin.page-tree-element', ['children' => $page->children])
                </li>
            @empty
            @endforelse
        @endif
    </ul>
</div>

