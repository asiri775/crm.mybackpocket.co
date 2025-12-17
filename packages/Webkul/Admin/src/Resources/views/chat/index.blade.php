@extends('admin::mail.layout.main')
@php
    $pageType = 'chat';
    if (!isset($chat)) {
        $chat = null;
    }
    $chatInfo = \App\Helpers\ChatHelper::chatInfo(auth()->user()->id, $chat);
@endphp
@section('mailLayoutPageTitle')
    @lang('admin::app.chat.index')
@endsection

@section('mailLayoutBreadcrumbs')
    <div
        class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        <div class="flex flex-col gap-2">
            <div class="flex cursor-pointer items-center">
                {!! view_render_event('admin.chat.index.breadcrumbs.before') !!}

                <!-- breadcrumbs -->
                <x-admin::breadcrumbs name="chat" />

                {!! view_render_event('admin.chat.index.breadcrumbs.after') !!}
            </div>

            <div class="text-xl font-bold dark:text-white">
                <!-- title -->
                @lang('admin::app.chat.index')
            </div>
        </div>
    </div>
@endsection

@section('mailLayoutBody')
    <div class="bootstrap-wrapper chat-ui-wrapper">
        <div class="layout-wrapper d-lg-flex">
            <!-- start chat-leftsidebar -->
            <div class="chat-leftsidebar me-lg-1 ms-lg-0">
                <div class="tab-content">
                    @include('admin::chat.components.sidebar', compact('chat'))
                </div>
            </div>
            <!-- end chat-leftsidebar -->

            <!-- Start User chat -->
            <div class="user-chat w-100 overflow-hidden">
                <?php
                    if($chatInfo['name']!=null){
                        ?>
                <div class="d-lg-flex">

                    <!-- start chat conversation section -->
                    <div class="w-100 overflow-hidden position-relative">
                        <div class="p-3 p-lg-4 border-bottom user-chat-topbar">
                            <div class="row align-items-center">
                                <div class="col-sm-4 col-8">
                                    <div class="d-flex align-items-center">
                                        <div class="d-block d-lg-none me-2 ms-0">
                                            <a href="javascript: void(0);"
                                                class="user-chat-remove text-muted font-size-16 p-2"><i
                                                    class="ri-arrow-left-s-line"></i></a>
                                        </div>
                                        <div class="me-3 ms-0">
                                            <img src="<?= $chatInfo['image'] ?>" class="rounded-circle avatar-xs"
                                                alt="<?= $chatInfo['name'] ?>">
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-0 text-truncate"><?= $chatInfo['name'] ?></h5>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="col-sm-8 col-4">
                                            <ul class="list-inline user-chat-nav text-end mb-0">
                                                <li class="list-inline-item">
                                                    <div class="dropdown">
                                                        <button class="btn nav-btn dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ri-search-line"></i>
                                                        </button>
                                                        <div class="dropdown-menu p-0 dropdown-menu-end dropdown-menu-md">
                                                            <div class="search-box p-2">
                                                                <input type="text" class="form-control bg-light border-0"
                                                                    placeholder="Search..">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
        
                                                <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                                                    <button type="button" class="btn nav-btn" data-bs-toggle="modal"
                                                        data-bs-target="#audiocallModal">
                                                        <i class="ri-phone-line"></i>
                                                    </button>
                                                </li>
        
                                                <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                                                    <button type="button" class="btn nav-btn" data-bs-toggle="modal"
                                                        data-bs-target="#videocallModal">
                                                        <i class="ri-vidicon-line"></i>
                                                    </button>
                                                </li>
        
                                                <li class="list-inline-item d-none d-lg-inline-block me-2 ms-0">
                                                    <button type="button" class="btn nav-btn user-profile-show">
                                                        <i class="ri-user-2-line"></i>
                                                    </button>
                                                </li>
        
                                                <li class="list-inline-item">
                                                    <div class="dropdown">
                                                        <button class="btn nav-btn dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="ri-more-fill"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item d-block d-lg-none user-profile-show"
                                                                href="#">View profile <i
                                                                    class="ri-user-2-line float-end text-muted"></i></a>
                                                            <a class="dropdown-item d-block d-lg-none" href="#"
                                                                data-bs-toggle="modal" data-bs-target="#audiocallModal">Audio <i
                                                                    class="ri-phone-line float-end text-muted"></i></a>
                                                            <a class="dropdown-item d-block d-lg-none" href="#"
                                                                data-bs-toggle="modal" data-bs-target="#videocallModal">Video <i
                                                                    class="ri-vidicon-line float-end text-muted"></i></a>
                                                            <a class="dropdown-item" href="#">Archive <i
                                                                    class="ri-archive-line float-end text-muted"></i></a>
                                                            <a class="dropdown-item" href="#">Muted <i
                                                                    class="ri-volume-mute-line float-end text-muted"></i></a>
                                                            <a class="dropdown-item" href="#">Delete <i
                                                                    class="ri-delete-bin-line float-end text-muted"></i></a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div> --}}
                            </div>
                        </div>
                        <!-- end chat user head -->

                        <!-- start chat conversation -->
                        <div class="chat-conversation p-3 p-lg-4" id="chatMessagesListMain">
                            <ul class="list-unstyled mb-0" id="chatMessagesList">
                                <li class="loading">Loading Messages...</li>
                            </ul>
                        </div>
                        <!-- end chat conversation end -->

                        <!-- start chat input section -->
                        <div class="chat-input-section p-3 p-lg-4 border-top mb-0" id="chatMsgBox">

                            <div class="row g-0">

                                <div class="col">
                                    @csrf
                                    <input id="chatMessageInput" type="text" class="form-control form-control-lg bg-light border-light"
                                        placeholder="Enter Message...">
                                </div>
                                <div class="col-auto">
                                    <div class="chat-input-links ms-md-2 me-md-0">
                                        <ul class="list-inline mb-0">
                                            {{-- <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Emoji">
                                                <button type="button"
                                                    class="btn btn-link text-decoration-none font-size-16 btn-lg waves-effect">
                                                    <i class="ri-emotion-happy-line"></i>
                                                </button>
                                            </li>
                                            <li class="list-inline-item" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="Attached File">
                                                <button type="button"
                                                    class="btn btn-link text-decoration-none font-size-16 btn-lg waves-effect">
                                                    <i class="ri-attachment-line"></i>
                                                </button>
                                            </li> --}}
                                            <li class="list-inline-item">
                                                <button type="button" id="sendMessage"
                                                    class="btn primary-button font-size-16 btn-lg chat-send waves-effect waves-light">
                                                    Send
                                                </button>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- end chat input section -->
                    </div>
                    <!-- end chat conversation section -->

                </div>
                <!-- End User chat -->
                <?php
                    }else{
                        ?>
                <div class="no-chat-selected">
                    <img width="100" height="100" src="{{ asset('images/icons8-chat-100.png') }}" alt="chat" />
                    <h6>Select Chat to show messages</h6>
                </div>
                <?php
                    }
                    ?>
            </div>
        </div>
    </div>
    <script>
        const SEARCH_CHAT_API_URL = "{{ route('user.chat.search') }}";
    </script>
    <?php
    if($chatInfo['name']!=null){
        ?>
    <script>
        const MESSAGES_API_URL = "{{ route('user.chat.messages', $chat->uuid) }}";
        const SEND_MESSAGE_API_URL = "{{ route('user.chat.send', $chat->uuid) }}";
        const CHAT_READ_API_URL = "{{ route('user.chat.read', $chat->uuid) }}";
    </script>
    <?php }?>
    <script src="{{ asset('assets/js/chat.js') }}?v={{ time() }}"></script>
@endsection
