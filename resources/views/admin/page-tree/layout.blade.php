<div class="bg-white rounded shadow-sm mb-3 page-tree-container">
    <div class="command-bar">
        <div class="search-bar">
            <input autocomplete="off" aria-autocomplete="none" type="text" name="name" placeholder="Поиск" id="search-tree">
        </div>
        <button type="button" class="uncollapse-all">Раскрыть всё</button>
        <button type="button" class="collapse-all">Скрыть всё</button>
    </div>

    <div class="list-container">
        @include('admin.page-tree.container')
    </div>
    <div id="result"></div>
</div>
