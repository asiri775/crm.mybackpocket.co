<?php
$user = auth()->user();
if($user->role_id == \App\Helpers\Constants::ADMIN_ROLE){
    ?>
@include('admin::dashboard.views.admin')
<?php
}else if($user->role_id == \App\Helpers\Constants::HOST_ROLE){
    ?>
@include('admin::dashboard.views.host')
<?php
}else if($user->role_id == \App\Helpers\Constants::GUEST_ROLE){
    ?>
@include('admin::dashboard.views.guest')
<?php
}else{
    ?>
<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.dashboard.index.title')
    </x-slot>

    <!-- Head Details Section -->
    {!! view_render_event('admin.dashboard.index.header.before') !!}

    <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
        {!! view_render_event('admin.dashboard.index.header.left.before') !!}

        <div class="grid gap-1.5">
            <p class="text-2xl font-semibold dark:text-white">
                @lang('admin::app.dashboard.index.title')
            </p>
        </div>

        {!! view_render_event('admin.dashboard.index.header.left.after') !!}

        <!-- Actions -->
        {!! view_render_event('admin.dashboard.index.header.right.before') !!}

        <v-dashboard-filters>
            <!-- Shimmer -->
            <div class="flex gap-1.5">
                <div class="light-shimmer-bg dark:shimmer h-[39px] w-[140px] rounded-md"></div>
                <div class="light-shimmer-bg dark:shimmer h-[39px] w-[140px] rounded-md"></div>
            </div>
        </v-dashboard-filters>

        {!! view_render_event('admin.dashboard.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.dashboard.index.header.after') !!}

    <!-- Body Component -->
    {!! view_render_event('admin.dashboard.index.content.before') !!}

    <h1>Not Found</h1>

    {!! view_render_event('admin.dashboard.index.content.after') !!}


</x-admin::layouts>
<?php
}
?>
