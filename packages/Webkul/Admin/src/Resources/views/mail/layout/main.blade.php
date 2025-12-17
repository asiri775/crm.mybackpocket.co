@php
    if (!isset($pageType)) {
        $pageType = 'mail';
    }
@endphp
<x-admin::layouts>
    <x-slot:title>
        @yield('mailLayoutPageTitle')
    </x-slot>
    <div class="flex flex-col gap-4">
        @yield('mailLayoutBreadcrumbs')
        <div class="mail-page-layout">
            <div class="mail-page-switch">
                <a class="primary-button <?= $pageType === 'mail' ? 'active' : '' ?>" href="{{route('admin.mail.index')}}">Mail</a>
                <a class="primary-button <?= $pageType === 'chat' ? 'active' : '' ?>" href="{{route('admin.chat.index')}}">Messages</a>
                <div class="search-u-names-chat">
                    <input id="search-chat-user" autocomplete="off" type="text" class="form-control" placeholder="Search for a Guest or Host">
                    <div class="search-results"></div>
                </div>
            </div>
            <div class="mail-page-body">
                @yield('mailLayoutBody')
            </div>
        </div>
    </div>
</x-admin::layouts>
