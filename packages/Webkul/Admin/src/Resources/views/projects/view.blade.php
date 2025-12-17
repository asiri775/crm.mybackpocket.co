<x-admin::layouts>
    <x-slot:title>
        @lang ($project->name)
    </x-slot>

    <!-- Content -->
    <div class="flex gap-4">
        <!-- Left Panel -->
        {!! view_render_event('admin.leads.view.left.before', ['project' => $project]) !!}

        <div
            class="sticky top-[73px] flex min-w-[394px] max-w-[394px] flex-col self-start rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- project Information -->
            <div class="flex w-full flex-col gap-2 border-b border-gray-200 p-4 dark:border-gray-800">
                <!-- Breadcrums -->
                <div class="flex items-center justify-between">
                    <x-admin::breadcrumbs name="projects.view" :entity="$project" />
                </div>

                <!-- Title -->
                <div class="mb-2 flex flex-col gap-0.5">
                    <h3 class="text-lg font-bold dark:text-white">
                        {{ $project->name }}
                    </h3>

                    <p class="text-sm font-normal dark:text-white">
                        @lang('admin::app.projects.view.projectId') : {{ $project->project_id }}
                    </p>
                </div>

            </div>

            <div class="dark: flex w-full flex-col gap-4 border-b border-gray-200 p-4 dark:border-gray-800">
                <div>
                    <form>
                        <div class="flex flex-col gap-1">
                            <div class="grid grid-cols-[1fr_2fr] items-center gap-1">
                                <div class="label dark:text-white">Category</div>
                                <div class="font-medium dark:text-white">
                                    <div class="group w-full max-w-full hover:rounded-sm">
                                        <div
                                            class="flex h-[34px] items-center rounded border border-transparent transition-all hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <div class="group relative !w-full pl-2.5" style="text-align: left;"><span
                                                    class="cursor-pointer truncate rounded"><?=$project->category->name?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-[1fr_2fr] items-center gap-1">
                                <div class="label dark:text-white">Start Date</div>
                                <div class="font-medium dark:text-white">
                                    <div class="group w-full max-w-full hover:rounded-sm">
                                        <div
                                            class="flex h-[34px] items-center rounded border border-transparent transition-all hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <div class="group relative !w-full pl-2.5" style="text-align: left;"><span
                                                    class="cursor-pointer truncate rounded"><?=$project->start_date?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-[1fr_2fr] items-center gap-1">
                                <div class="label dark:text-white">Deadline</div>
                                <div class="font-medium dark:text-white">
                                    <div class="group w-full max-w-full hover:rounded-sm">
                                        <div
                                            class="flex h-[34px] items-center rounded border border-transparent transition-all hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <div class="group relative !w-full pl-2.5" style="text-align: left;"><span
                                                    class="cursor-pointer truncate rounded"><?=$project->deadline?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-[1fr_2fr] items-center gap-1">
                                <div class="label dark:text-white">Phases</div>
                                <div class="font-medium dark:text-white">
                                    <div class="group w-full max-w-full hover:rounded-sm">
                                        <div
                                            class="flex h-[34px] items-center rounded border border-transparent transition-all hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <div class="group relative !w-full pl-2.5" style="text-align: left;"><span
                                                    class="cursor-pointer truncate rounded"><?=$project->phases?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-[1fr_2fr] items-center gap-1">
                                <div class="label dark:text-white">Description</div>
                                <div class="font-medium dark:text-white">
                                    <div class="group w-full max-w-full hover:rounded-sm">
                                        <div
                                            class="flex h-[34px] items-center rounded border border-transparent transition-all hover:bg-gray-100 dark:hover:bg-gray-800">
                                            <div class="group relative !w-full pl-2.5" style="text-align: left;"><span
                                                    class="cursor-pointer truncate rounded"><?=$project->description?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        {!! view_render_event('admin.projects.view.left.after', ['project' => $project]) !!}

        {!! view_render_event('admin.projects.view.right.before', ['project' => $project]) !!}

        <!-- Right Panel -->
        <div class="flex w-full flex-col gap-4 rounded-lg">
            <h3 class="text-lg font-bold dark:text-white">
                Tasks
            </h3>
            <x-admin::datagrid :src="route('admin.projects.tasks.index', ['projectId' => $project->id])">
                <!-- DataGrid Shimmer -->
                <x-admin::shimmer.datagrid />
            </x-admin::datagrid>
        </div>

        {!! view_render_event('admin.projects.view.right.after', ['project' => $project]) !!}
    </div>
</x-admin::layouts>
