<!DOCTYPE html>

<html class="{{ request()->cookie('dark_mode') ? 'dark' : '' }}" lang="{{ app()->getLocale() }}"
    dir="{{ in_array(app()->getLocale(), ['fa', 'ar']) ? 'rtl' : 'ltr' }}">

<head>

    {!! view_render_event('admin.layout.head.before') !!}

    <title>{{ $title ?? '' }}</title>

    <meta charset="UTF-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="{{ url()->to('/') }}">
    <meta name="currency"
        content="{{ json_encode([
            'code' => config('app.currency'),
            'symbol' => core()->currencySymbol(config('app.currency')),
        ]) }}
        ">

    @stack('meta')

    {{ vite()->set(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js']) }}

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet" />

    <link rel="preload" as="image" href="{{ url('cache/logo/bagisto.png') }}">

    <link rel="icon" type="image/png" href="{{ asset('/images/fav-main.png') }}" />

    @stack('styles')

    <style>
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <link rel= "stylesheet"
        href= "https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />


    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}?v={{ time() }}">

    <script>
        const SEARCH_URL_API = "{{route('searchUsers')}}";
    </script>
    

    {!! view_render_event('admin.layout.head.after') !!}
</head>

<body class="h-full font-inter dark:bg-gray-950">
    {!! view_render_event('admin.layout.body.before') !!}

    <div id="app" class="h-full">
        <!-- Flash Message Blade Component -->
        <x-admin::flash-group />
 
        <!-- Confirm Modal Blade Component -->
        <x-admin::modal.confirm />

        {!! view_render_event('admin.layout.content.before') !!}

        <!-- Page Header Blade Component -->
        <x-admin::layouts.header />

        <div class="group/container sidebar-collapsed flex gap-4" ref="appLayout">

            <?php
                $user = auth()->user();
                if($user->role_id === \App\Helpers\Constants::GUEST_ROLE){

                    ?>
            {{-- <div class="mail-box-menu">
                <x-admin::layouts.sidebar /> 
            </div> --}}

            <?php } ?>

            <div class="max-w-full flex-1 bg-gray-100 transition-all duration-300 dark:bg-gray-950 main-box-layout">
                <!-- Page Content Blade Component -->
                {{ $slot }}
            </div>
        </div>

        {!! view_render_event('admin.layout.content.after') !!}
    </div>

    {!! view_render_event('admin.layout.body.after') !!}

    @stack('scripts')

    {!! view_render_event('admin.layout.vue-app-mount.before') !!}

    <script>
        /**
         * Load event, the purpose of using the event is to mount the application
         * after all of our `Vue` components which is present in blade file have
         * been registered in the app. No matter what `app.mount()` should be
         * called in the last.
         */
        window.addEventListener("load", function(event) {
            app.mount("#app");
        });
    </script>

    {!! view_render_event('admin.layout.vue-app-mount.after') !!}

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script src="{{ asset('assets/js/inputmask.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}?v={{ time() }}"></script>

</body>

</html>
