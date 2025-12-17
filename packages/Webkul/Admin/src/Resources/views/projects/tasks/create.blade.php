<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.projects.tasks.create.title')
    </x-slot>

    {!! view_render_event('admin.projects.tasks.create.form.before') !!}

    <x-admin::form :action="route('admin.projects.tasks.store')" method="POST">
        <div class="flex flex-col gap-4">
            <div
                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="flex cursor-pointer items-center">
                        {!! view_render_event('admin.projects.tasks.create.breadcrumbs.before') !!}

                        <!-- Breadcrumbs -->
                        <x-admin::breadcrumbs name="projects.tasks.create" />

                        {!! view_render_event('admin.projects.tasks.create.breadcrumbs.after') !!}
                    </div>

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.projects.tasks.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                        {!! view_render_event('admin.projects.tasks.create.save_button.before') !!}

                        <!-- Create button for Product -->
                        @if (bouncer()->hasPermission('projects.tasks.create'))
                            <button type="submit" class="primary-button">
                                @lang('admin::app.projects.tasks.create.save-btn')
                            </button>
                        @endif

                        {!! view_render_event('admin.projects.tasks.create.save_button.after') !!}
                    </div>
                </div>
            </div>

            <div class="flex gap-2.5 max-xl:flex-wrap">
                <!-- Left sub-component -->
                <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.products.create.general')
                        </p>

                        <div class="grid grid-cols-2 gridcol-simple gap-4">

                            <div class="grid grid-cols-1 gap-4">
                                <x-admin::attributes :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')
                                    ->findWhere([
                                        'entity_type' => 'projects-tasks',
                                    ])
                                    ->whereNotIn('code', ['description'])" :entity="$projectTask" />
                            </div>

                            <div class="grid grid-cols-1 gap-4 big-txtarea">

                                <x-admin::attributes :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                    'entity_type' => 'projects',
                                    ['code', 'IN', ['description']],
                                ])" />

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </x-admin::form>

    {!! view_render_event('admin.projects.tasks.create.form.after') !!}
</x-admin::layouts>
