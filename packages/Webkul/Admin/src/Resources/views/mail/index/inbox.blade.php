{!! view_render_event('admin.mail.inbox.datagrid.before') !!}

<x-admin::datagrid :src="route('admin.mail.index', ['route' => 'inbox'])">
    <!-- DataGrid Shimmer -->
    <x-admin::shimmer.mail.datagrid />
</x-admin::datagrid>

{!! view_render_event('admin.mail.inbox.datagrid.after') !!}
