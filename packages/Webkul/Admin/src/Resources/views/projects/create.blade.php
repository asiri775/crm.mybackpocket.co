<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.projects.create.title')
    </x-slot>

    {!! view_render_event('admin.projects.create.form.before') !!}

    <x-admin::form :action="route('admin.projects.store')" method="POST">

        <div class="flex flex-col gap-4 project-form">
            <div
                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="flex cursor-pointer items-center">
                        {!! view_render_event('admin.projects.create.breadcrumbs.before') !!}

                        <!-- Breadcrumbs -->
                        <x-admin::breadcrumbs name="projects.create" />

                        {!! view_render_event('admin.projects.create.breadcrumbs.after') !!}
                    </div>

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.projects.create.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <div class="flex items-center gap-x-2.5">
                        {!! view_render_event('admin.projects.create.save_button.before') !!}

                        <!-- Create button for Product -->
                        @if (bouncer()->hasPermission('projects.create'))
                            <button type="submit" class="primary-button">
                                @lang('admin::app.projects.create.save-btn')
                            </button>
                        @endif

                        {!! view_render_event('admin.projects.create.save_button.after') !!}
                    </div>
                </div>
            </div>

            <div class="flex gap-2.5 max-xl:flex-wrap">
                <!-- Left sub-component -->
                <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                    <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:bg-gray-900">
                        <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                            @lang('admin::app.projects.create.general')
                        </p>

                        <div class="grid grid-cols-2 gridcol-simple gap-4">

                            <div class="grid grid-cols-1 gap-4">

                                <div class="mb-4 mb-2.5 w-full projectId-thing">
                                    <label
                                        class="mb-1.5 flex items-center gap-1 text-sm font-normal text-gray-800 dark:text-white required"
                                        for="project_id"> Project </label>
                                    <span class="prefix">{{ \App\Helpers\Constants::PROJECT_PREFIX }}</span>
                                    <input type="text" name="project_id"
                                        class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm font-normal text-gray-800 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400"
                                        id="project_id" value="{{ $projectId ?? 101 }}" readonly>
                                </div>

                                <x-admin::attributes :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')
                                    ->findWhere([
                                        'entity_type' => 'projects',
                                    ])
                                    ->whereNotIn('code', ['description', 'phases', 'project_id', 'client_id'])" />

                                <x-admin::attributes :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                    'entity_type' => 'projects',
                                    ['code', 'IN', ['phases']],
                                ])" />

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

    {!! view_render_event('admin.projects.create.form.after') !!}

</x-admin::layouts>
