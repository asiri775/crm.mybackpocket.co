<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.projects.categories.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="flex cursor-pointer items-center">
                    <!-- Breadcrumbs -->
                    <x-admin::breadcrumbs name="projects.categories" />
                </div>

                <div class="text-xl font-bold dark:text-white">
                    @lang('admin::app.projects.categories.index.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <!-- Create button for Project -->
                @if (bouncer()->hasPermission('projects.create'))
                    <div class="flex items-center gap-x-2.5">
                        <a
                            href="{{ route('admin.projects.categories.create') }}"
                            class="primary-button"
                        >
                            @lang('admin::app.projects.categories.index.create-btn')
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <x-admin::datagrid :src="route('admin.projects.categories.index')">
            <!-- DataGrid Shimmer -->
            <x-admin::shimmer.datagrid />
        </x-admin::datagrid>
    </div>
</x-admin::layouts>
