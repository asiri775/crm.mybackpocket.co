<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.projects.tasks.edit.title')
    </x-slot>

    {!! view_render_event('admin.projects.tasks.edit.form.before') !!}

    <x-admin::form :action="route('admin.projects.tasks.update', $projectTask->id)" method="PUT">
        <div class="flex flex-col gap-4">
            <div
                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="flex cursor-pointer items-center">
                        <!-- Breadcrumbs -->
                        <x-admin::breadcrumbs name="projects.tasks.edit" :entity="$projectTask" />
                    </div>

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.projects.tasks.edit.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <!-- Edit button for Project Category -->
                    <div class="flex items-center gap-x-2.5">
                        <button type="submit" class="primary-button">
                            @lang('admin::app.projects.tasks.create.save-btn')
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex gap-2.5 max-xl:flex-wrap">
                <!-- Left sub-component -->
                <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.projects.tasks.create.general')
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
                                ])" :entity="$projectTask" />

                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </x-admin::form>

    {!! view_render_event('admin.projects.tasks.edit.form.after') !!}

</x-admin::layouts>
