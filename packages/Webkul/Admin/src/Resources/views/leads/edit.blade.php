<x-admin::layouts>
    <!-- Page Title -->
    <x-slot:title>
        @lang('admin::app.leads.edit.title')
    </x-slot>

    {!! view_render_event('admin.leads.edit.form.before') !!}

    <!-- Edit Lead Form -->
    <x-admin::form         
        :action="route('admin.leads.update', $lead->id)"
        method="PUT"
    >
        <div class="flex flex-col gap-4">

            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <div class="flex cursor-pointer items-center">
                        <x-admin::breadcrumbs 
                            name="leads.edit" 
                            :entity="$lead"
                        />
                    </div>

                    <div class="text-xl font-bold dark:text-white">
                        @lang('admin::app.leads.edit.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <!-- Save button for Editing Lead -->
                    <div class="flex items-center gap-x-2.5">
                        {!! view_render_event('admin.leads.edit.form_buttons.before') !!}

                        <button
                            type="submit"
                            class="primary-button"
                        >
                            @lang('admin::app.leads.edit.save-btn')
                        </button>

                        {!! view_render_event('admin.leads.edit.form_buttons.after') !!}
                    </div>
                </div>
            </div>

            <input type="hidden" id="lead_pipeline_stage_id" name="lead_pipeline_stage_id" value="{{ $lead->lead_pipeline_stage_id }}" />

            <!-- Lead Edit Component -->
            <v-lead-edit :lead="{{ json_encode($lead) }}"></v-lead-edit>
        </div>
    </x-admin::form>

    {!! view_render_event('admin.leads.edit.form.after') !!}

    @pushOnce('scripts')
        <script 
            type="text/x-template"
            id="v-lead-edit-template"
        >
            <div class="box-shadow flex flex-col gap-4 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 max-xl:flex-wrap">
                {!! view_render_event('admin.leads.edit.form_controls.before') !!}

                <div class="flex gap-2 border-b border-gray-200 dark:border-gray-800">
                    <a href="#lead-details" class="inline-block px-3 py-2.5 border-b-2  text-sm font-medium text-gray-600 dark:text-gray-300  border-transparent hover:text-gray-800 hover:border-gray-400 dark:hover:border-gray-400  dark:hover:text-white">Details</a>
                    <a href="#booking-dates" class="inline-block px-3 py-2.5 border-b-2  text-sm font-medium text-gray-600 dark:text-gray-300  border-transparent hover:text-gray-800 hover:border-gray-400 dark:hover:border-gray-400  dark:hover:text-white">Booking Dates</a>
                    <a href="#contact-person" class="inline-block px-3 py-2.5 border-b-2  text-sm font-medium text-gray-600 dark:text-gray-300  border-transparent hover:text-gray-800 hover:border-gray-400 dark:hover:border-gray-400  dark:hover:text-white">Contact Persons</a>
                </div>

                <div class="flex flex-col gap-4 px-4 py-2">
                    <!-- Details section -->
                    <div 
                        class="flex flex-col gap-4" 
                        id="lead-details"
                    >
                        <div class="flex flex-col gap-1">
                            <p class="text-base font-semibold dark:text-white">
                                @lang('admin::app.leads.edit.details')
                            </p>

                            <p class="text-gray-600 dark:text-white">
                                @lang('admin::app.leads.edit.details-info')
                            </p>
                        </div>

                        <div class="w-1/2">
                            <!-- Lead Details Title and Description -->
                            <x-admin::attributes
                                :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                    ['code', 'NOTIN', ['lead_value', 'lead_type_id', 'lead_source_id', 'expected_close_date', 'user_id', 'lead_pipeline_id', 'lead_pipeline_stage_id', 'start_date', 'end_date', 'start_time', 'end_time', 'booking_type', 'space_id']],
                                    'entity_type' => 'leads',
                                    'quick_add'   => 1
                                ])"
                                :custom-validations="[
                                    'expected_close_date' => [
                                        'date_format:yyyy-MM-dd',
                                        'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                    ],
                                ]"
                                :entity="$lead"
                            />

                            <!-- Lead Details Other input fields -->
                            <div class="flex gap-4 max-sm:flex-wrap">
                                <div class="w-full">
                                    <x-admin::attributes
                                        :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                            ['code', 'IN', [ 'lead_source_id']],
                                            'entity_type' => 'leads',
                                            'quick_add'   => 1
                                        ])"
                                        :custom-validations="[
                                            'expected_close_date' => [
                                                'date_format:yyyy-MM-dd',
                                                'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                            ],
                                        ]"
                                        :entity="$lead"
                                    />
                                </div>
                                    
                                <div class="w-full">
                                    <x-admin::attributes
                                        :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                            ['code', 'IN', [ 'lead_value']],
                                            'entity_type' => 'leads',
                                            'quick_add'   => 1
                                        ])"
                                        :entity="$lead"
                                        />
                                </div>
                            </div>

                            <div class="flex gap-4 max-sm:flex-wrap">
                                <div class="w-full">
                                    <x-admin::attributes
                                        :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                            ['code', 'IN', ['lead_type_id']],
                                            'entity_type' => 'leads',
                                            'quick_add'   => 1
                                        ])"
                                        :custom-validations="[
                                            'expected_close_date' => [
                                                'date_format:yyyy-MM-dd',
                                                'after:' .  \Carbon\Carbon::yesterday()->format('Y-m-d')
                                            ],
                                        ]"
                                        :entity="$lead"
                                    />
                                </div>
                                    
                                <div class="w-full">
                                    <x-admin::attributes
                                        :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                            ['code', 'IN', ['space_id']],
                                            'entity_type' => 'leads',
                                            'quick_add'   => 1
                                        ])"
                                        :entity="$lead"
                                        />
                                </div>
                            </div>

                            <div class="flex flex-col gap-1 mb-5 mt-4" id="booking-dates">
                                <p class="text-base font-semibold dark:text-white">Booking Dates</p>
                                <p class="text-gray-600 dark:text-white"> Information About the booking</p>
                            </div>

                            <div class="flex gap-4 max-sm:flex-wrap">
                                <x-admin::attributes
                                    :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->scopeQuery(function ($query) {
                                        return $query->whereIn('code', ['start_date', 'start_time'])
                                                    ->where('entity_type', 'leads')
                                                    ->where('quick_add', 1)
                                                    ->orderBy('sort_order', 'asc');
                                    })->get()"
                                    :entity="$lead"
                                />
                            </div>

                            <div class="flex gap-4 max-sm:flex-wrap">
                                <x-admin::attributes
                                    :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->scopeQuery(function ($query) {
                                        return $query->whereIn('code', ['end_date', 'end_time'])
                                                    ->where('entity_type', 'leads')
                                                    ->where('quick_add', 1)
                                                    ->orderBy('sort_order', 'asc');
                                    })->get()"
                                    :entity="$lead"
                                />
                            </div>

                            <div class="flex gap-4 max-sm:flex-wrap">
                                <x-admin::attributes
                                    :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                                        ['code', 'IN', ['booking_type']],
                                        'entity_type' => 'leads',
                                        'quick_add'   => 1
                                    ])"
                                    :entity="$lead"
                                />
                            </div>

                        </div>
                    </div>

                    <!-- Contact Person -->
                    <div 
                        class="flex flex-col gap-4" 
                        id="contact-person"
                    >
                        <div class="flex flex-col gap-1">
                            <p class="text-base font-semibold dark:text-white">
                                @lang('admin::app.leads.edit.contact-person')
                            </p>

                            <p class="text-gray-600 dark:text-white">
                                @lang('admin::app.leads.edit.contact-info')
                            </p>
                        </div>

                        <div class="w-1/2">
                            <!-- Contact Person Component -->
                            @include('admin::leads.common.contact')
                        </div>
                    </div>

                    <div class="flex flex-col mb-5" style="margin-top:-15px;">
                        <div class="">
                            <button type="submit" class="primary-button">
                                @lang('admin::app.leads.create.save-btn')
                            </button>
                        </div>
                    </div>

                   
                </div>
                
                {!! view_render_event('admin.leads.form_controls.after') !!}
            </div>
        </script>

        <script type="module">
            app.component('v-lead-edit', {
                template: '#v-lead-edit-template',

                data() {
                    return {
                        activeTab: 'lead-details',
                        
                        lead:  @json($lead),  

                        person:  @json($lead->person),  

                        products: @json($lead->products),

                        tabs: [
                            { id: 'lead-details', label: '@lang('admin::app.leads.edit.details')' },
                            { id: 'contact-person', label: '@lang('admin::app.leads.edit.contact-person')' },
                        ],
                    };
                },

                methods: {
                    /**
                     * Scroll to the section.
                     * 
                     * @param {String} tabId
                     * 
                     * @returns {void}
                     */
                    scrollToSection(tabId) {
                        const section = document.getElementById(tabId);

                        if (section) {
                            section.scrollIntoView({ behavior: 'smooth' });
                        }
                    },
                },
            });
        </script>
    @endPushOnce

    @pushOnce('styles')
        <style>
            html {
                scroll-behavior: smooth;
            }
        </style>
    @endPushOnce    
</x-admin::layouts>