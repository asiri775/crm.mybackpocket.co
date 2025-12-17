<x-admin::layouts>
    <x-slot:title>
        @lang ($projectTask->name)
    </x-slot>

    <!-- Content -->
    <div class="flex gap-4">
        <!-- Left Panel -->
        {!! view_render_event('admin.projects.tasks.view.left.before', ['projectTask' => $projectTask]) !!}

        <div class="sticky top-[73px] w-full flex w-100 flex-col self-start rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900">
            <!-- Product Information -->
            <div class="flex w-full flex-col gap-2 border-b border-gray-200 p-4 dark:border-gray-800">
                <!-- Breadcrums -->
                <div class="flex items-center justify-between">
                    <x-admin::breadcrumbs name="projects.tasks.view" :entity="$projectTask" />
                </div>

                <!-- Title -->
                <div class="mb-2 flex flex-col gap-0.5">
                    <h3 class="text-lg font-bold dark:text-white">
                        {{ $projectTask->name }}
                    </h3>
                </div>

            </div>
            
        </div>

        {!! view_render_event('admin.projects.tasks.view.left.after', ['projectTask' => $projectTask]) !!}

        {!! view_render_event('admin.projects.tasks.view.right.before', ['projectTask' => $projectTask]) !!}
        
        {!! view_render_event('admin.projects.tasks.view.right.after', ['projectTask' => $projectTask]) !!}
    </div>    
</x-admin::layouts>