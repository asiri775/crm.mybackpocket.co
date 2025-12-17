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

        <div class="flex gap-1.5">
            @if (bouncer()->hasPermission('leads.create'))
                <a href="{{ route('admin.leads.create') }}" class="primary-button">
                    @lang('admin::app.leads.index.create-btn')
                </a>
            @endif
            <v-dashboard-filters>
                <!-- Shimmer -->
                <div class="flex gap-1.5">
                    <div class="light-shimmer-bg dark:shimmer h-[39px] w-[140px] rounded-md"></div>
                    <div class="light-shimmer-bg dark:shimmer h-[39px] w-[140px] rounded-md"></div>
                </div>
            </v-dashboard-filters>
        </div>

        {!! view_render_event('admin.dashboard.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.dashboard.index.header.after') !!}

    <!-- Body Component -->
    {!! view_render_event('admin.dashboard.index.content.before') !!}

    <div class="mt-3.5 flex gap-4 max-xl:flex-wrap">
        <!-- Left Section -->
        {!! view_render_event('admin.dashboard.index.content.left.before') !!}

        <div class="flex flex-1 flex-col gap-4 max-xl:flex-auto">

            <!-- Over All Stats -->
            {{-- @include('admin::dashboard.index.over-all') --}}

            {{-- @include('admin::dashboard.index.open-leads-by-states') --}}

            <div
                class="grid gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col justify-between gap-1">
                    <p class="text-base font-semibold dark:text-gray-300">Inbox</p>
                </div>
                <div class="relative flex w-full max-w-full flex-col gap-4">
                    @include('admin::mail.index.inbox')
                </div>
            </div>

            <div
                class="grid gap-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col justify-between gap-1">
                    <p class="text-base font-semibold dark:text-gray-300">Leads</p>
                </div>
                <div class="relative flex w-full max-w-full flex-col gap-4">
                    @include('admin::leads.index.table')
                </div>
            </div>

            <!-- Total Leads Stats -->
            @include('admin::dashboard.index.total-leads')

        </div>

        {!! view_render_event('admin.dashboard.index.content.left.after') !!}


    </div>

    {!! view_render_event('admin.dashboard.index.content.after') !!}

    @pushOnce('scripts')
        <script type="module" src="{{ vite()->asset('js/chart.js') }}"></script>

        <script type="module" src="https://cdn.jsdelivr.net/npm/chartjs-chart-funnel@4.2.1/build/index.umd.min.js"></script>

        <script
            type="text/x-template"
            id="v-dashboard-filters-template"
        >
            <div class="flex gap-1.5">
                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                    <input
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                        v-model="filters.start"
                        placeholder="@lang('admin::app.dashboard.index.start-date')"
                    />
                </x-admin::flat-picker.date>

                <x-admin::flat-picker.date class="!w-[140px]" ::allow-input="false">
                    <input
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                        v-model="filters.end"
                        placeholder="@lang('admin::app.dashboard.index.end-date')"
                    />
                </x-admin::flat-picker.date>
            </div>
        </script>

        <script type="module">
            app.component('v-dashboard-filters', {
                template: '#v-dashboard-filters-template',

                data() {
                    return {
                        filters: {
                            channel: '',

                            start: "{{ $startDate->format('Y-m-d') }}",

                            end: "{{ $endDate->format('Y-m-d') }}",
                        }
                    }
                },

                watch: {
                    filters: {
                        handler() {
                            this.$emitter.emit('reporting-filter-updated', this.filters);
                        },

                        deep: true
                    }
                },
            });
        </script>
    @endPushOnce
</x-admin::layouts>
