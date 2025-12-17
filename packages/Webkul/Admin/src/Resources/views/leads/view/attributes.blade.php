{!! view_render_event('admin.leads.view.attributes.before', ['lead' => $lead]) !!}

<div class="dark: flex w-full flex-col border-b border-gray-200 p-4 dark:border-gray-800">
    <h4 class="flex items-center justify-between font-semibold dark:text-white">
        @lang('admin::app.leads.view.attributes.title')

        @if (bouncer()->hasPermission('leads.edit'))
            <a
                href="{{ route('admin.leads.edit', $lead->id) }}"
                class="icon-edit rounded-md p-1 text-2xl transition-all hover:bg-gray-100 dark:hover:bg-gray-950"
                target="_blank"
            ></a>
        @endif
    </h4>

    <x-admin::form
        v-slot="{ meta, errors, handleSubmit }"
        as="div"
        ref="modalForm"
    >
        <form @submit="handleSubmit($event, () => {})">
            <x-admin::attributes.view
                :custom-attributes="app('Webkul\Attribute\Repositories\AttributeRepository')->findWhere([
                    'entity_type' => 'leads',
                    ['code', 'NOTIN', ['title', 'description', 'lead_pipeline_id', 'user_id', 'lead_pipeline_stage_id', 'lead_value', 'expected_close_date']]
                ])"
                :entity="$lead" 
                :url="route('admin.leads.attributes.update', $lead->id)"
                :allow-edit="true"
            />
        </form>
    </x-admin::form>

    <?php
    
    $spaceId = $lead->space_id;
    if ($spaceId != null) {
        $space = \App\Models\Space::where('id', $spaceId)->first();
        if ($space != null) {
            $spaceLink = "<a target=\"_blank\" class=\"text-brandColor transition-all hover:underline\" href='" . env('MAIN_APP_URL') . '/space/' . $space->slug . "'>" . $space->title . '</a>';
            ?>
            <div class="grid grid-cols-[1fr_2fr] mt-1 items-center gap-1">
                <div class="label dark:text-white">Space</div>
                <div class="font-medium dark:text-white">
                    <div class="group w-full max-w-full hover:rounded-sm">
                        <div class="flex h-[34px] items-center rounded border border-transparent transition-all hover:bg-gray-100 dark:hover:bg-gray-800">
                            <input type="hidden" name="lead_source_id" class="w-full rounded border border-gray-200 px-2.5 py-2 text-sm font-normal text-gray-800 transition-all hover:border-gray-400 focus:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400 dark:focus:border-gray-400" id="lead_source_id" value="2">
                            <div class="group relative !w-full pl-2.5" style="text-align: left;">
                                <span class="cursor-pointer truncate rounded">{!!$spaceLink!!}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    ?>

<div class="mt-1">
</br>
    <hr/>
</br>
    <div class="mb-3 label dark:text-white"><strong>Description</strong></div>
    <div class="dark:text-white">
        {!!$lead->description!!}
    </div>
</div>

    
</div>

{!! view_render_event('admin.leads.view.attributes.before', ['lead' => $lead]) !!}
