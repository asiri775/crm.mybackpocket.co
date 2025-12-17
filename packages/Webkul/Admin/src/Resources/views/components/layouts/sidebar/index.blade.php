<div ref="sidebar" class="main-menu-inner" @mouseover="handleMouseOver" @mouseleave="handleMouseLeave">

    <div class="main-menu-mob-header">
        <a href="{{ route('admin.dashboard.index') }}">
            <img class="header-logo" src="https://myoffice.mybackpocket.co/user_assets/img/logo_black.png" id="logo-image"
                alt="{{ config('app.name') }}" />
        </a>
        <a href="javascript:;" class="close-main-menu">
            <i class="las la-times"></i>
        </a>
    </div>

    <div
        class="main-menu-box journal-scroll overflow-hidden group-[.sidebar-collapsed]/container:overflow-visible">
        <nav class="sidebar-rounded grid w-full gap-2">

            <?php
                $user = auth()->user();
                if($user->role_id === \App\Helpers\Constants::GUEST_ROLE){

                    ?>

            <div class="group/item mainmenu-menu-item mail-menu-item">
                <a class="flex gap-2 p-1.5 items-center cursor-pointer hover:rounded-lg bg-brandColor rounded-lg peer"
                    href="{{ route('admin.mail.index', ['route' => 'inbox']) }}">
                    <span class="icon-mail text-2xl text-white"></span>
                    <div
                        class="flex-1 flex justify-between items-center text-gray-600 dark:text-gray-300 font-medium whitespace-nowrap group-[.sidebar-collapsed]/container:hidden text-white group">
                        <p>Inbox</p>
                    </div>
                </a>
            </div>

            <div class="group/item mainmenu-menu-item mail-menu-item">
                <a class="flex gap-2 p-1.5 items-center cursor-pointer hover:rounded-lg bg-brandColor rounded-lg peer"
                    href="{{ route('admin.mail.index', ['route' => 'draft']) }}">
                    <span class="icon-mail text-2xl text-white"></span>
                    <div
                        class="flex-1 flex justify-between items-center text-gray-600 dark:text-gray-300 font-medium whitespace-nowrap group-[.sidebar-collapsed]/container:hidden text-white group">
                        <p>Draft</p>
                    </div>
                </a>
            </div>

            <div class="group/item mainmenu-menu-item mail-menu-item">
                <a class="flex gap-2 p-1.5 items-center cursor-pointer hover:rounded-lg bg-brandColor rounded-lg peer"
                    href="{{ route('admin.mail.index', ['route' => 'outbox']) }}">
                    <span class="icon-mail text-2xl text-white"></span>
                    <div
                        class="flex-1 flex justify-between items-center text-gray-600 dark:text-gray-300 font-medium whitespace-nowrap group-[.sidebar-collapsed]/container:hidden text-white group">
                        <p>Outbox</p>
                    </div>
                </a>
            </div>

            <div class="group/item mainmenu-menu-item mail-menu-item">
                <a class="flex gap-2 p-1.5 items-center cursor-pointer hover:rounded-lg bg-brandColor rounded-lg peer"
                    href="{{ route('admin.mail.index', ['route' => 'sent']) }}">
                    <span class="icon-mail text-2xl text-white"></span>
                    <div
                        class="flex-1 flex justify-between items-center text-gray-600 dark:text-gray-300 font-medium whitespace-nowrap group-[.sidebar-collapsed]/container:hidden text-white group">
                        <p>Sent</p>
                    </div>
                </a>
            </div>

            <div class="group/item mainmenu-menu-item mail-menu-item">
                <a class="flex gap-2 p-1.5 items-center cursor-pointer hover:rounded-lg bg-brandColor rounded-lg peer"
                    href="{{ route('admin.mail.index', ['route' => 'trash']) }}">
                    <span class="icon-mail text-2xl text-white"></span>
                    <div
                        class="flex-1 flex justify-between items-center text-gray-600 dark:text-gray-300 font-medium whitespace-nowrap group-[.sidebar-collapsed]/container:hidden text-white group">
                        <p>Trash</p>
                    </div>
                </a>
            </div>

            <?php
            
                    }else{
                ?>

            @foreach (menu()->getItems('admin') as $menuItem)
                <div class="group/item mainmenu-menu-item {{ $menuItem->isActive() ? 'active' : 'inactive' }}">
                    <a class="flex gap-2 p-1.5 items-center cursor-pointer hover:rounded-lg {{ $menuItem->isActive() == 'active' ? 'bg-brandColor rounded-lg' : ' hover:bg-gray-100 hover:dark:bg-gray-950' }} peer"
                        href="{{ !in_array($menuItem->getKey(), ['settings', 'configuration']) && $menuItem->haveChildren() ? 'javascript:void(0)' : $menuItem->getUrl() }}"
                        @mouseleave="!isMenuActive ? hoveringMenu = '' : {}"
                        @mouseover="hoveringMenu='{{ $menuItem->getKey() }}'" @click="isMenuActive = !isMenuActive">
                        <span
                            class="{{ $menuItem->getIcon() }} text-2xl {{ $menuItem->isActive() ? 'text-white' : '' }}"></span>

                        <div
                            class="flex-1 flex justify-between items-center text-gray-600 dark:text-gray-300 font-medium whitespace-nowrap group-[.sidebar-collapsed]/container:hidden {{ $menuItem->isActive() ? 'text-white' : '' }} group">
                            <p>{{ $menuItem->getName() }}</p>

                            @if (!in_array($menuItem->getKey(), ['settings', 'configuration']) && $menuItem->haveChildren())
                                <i
                                    class="icon-right-arrow rtl:icon-left-arrow invisible text-2xl group-hover/item:visible {{ $menuItem->isActive() ? 'text-white' : '' }}"></i>
                            @endif
                        </div>
                    </a>

                    <!-- Submenu -->
                    @if (!in_array($menuItem->getKey(), ['settings', 'configuration']) && $menuItem->haveChildren())
                        <div class="sidebar-menu-dropdown"
                            :class="[isMenuActive && (hoveringMenu == '{{ $menuItem->getKey() }}') ? '!flex' : 'hidden']">
                            <div class="sidebar-menu-dropdown-inner">
                                <div class="sidebar-menu-dropdown-box overflow-hidden">
                                    <nav class="grid w-full gap-2">
                                        @foreach ($menuItem->getChildren() as $subMenuItem)
                                            <div
                                                class="sidebar-menu-dropdown-item group/item {{ $menuItem->isActive() ? 'active' : 'inactive' }}">
                                                <a href="{{ $subMenuItem->getUrl() }}"
                                                    class="">{{ $subMenuItem->getName() }}</a>
                                            </div>
                                        @endforeach
                                    </nav>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach

            <?php } ?>

        </nav>
    </div>
</div>
