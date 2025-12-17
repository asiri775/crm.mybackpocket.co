{!! view_render_event('admin.leads.index.table.before') !!}

<div class="leads-data-table">
    <x-admin::datagrid :src="route('admin.leads.index')">
        <!-- DataGrid Shimmer -->
        <x-admin::shimmer.datagrid />

        <x-slot:toolbar-right-after>
            @include('admin::leads.index.view-switcher')
        </x-slot>
    </x-admin::datagrid>
</div>

{!! view_render_event('admin.leads.index.table.after') !!}
