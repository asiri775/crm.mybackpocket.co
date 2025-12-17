<div class="tab-pane fade show active" id="pills-chat" role="tabpanel" aria-labelledby="pills-chat-tab">
    <!-- Start chats content -->
    <div>
        <div class="px-3 pt-3">
            <div class="message-heading">
                <h4>Messages</h4> 
                    <?php
                    if(auth()->user()->id!==\App\Helpers\Constants::ADMIN_USER_ID){
                    ?>
                <a href="{{ route('user.chat.support') }}" class="primary-button">Support</a>
                <?php
                    }
                ?>
            </div>
            <div class="search-box chat-search-box">

                <div class="input-group input-group-main">
                    <input type="text" class="form-control bg-light" placeholder="Search Messages or Usernames" aria-label="Search"
                        aria-describedby="basic-addon1" id="chatSearch">
                </div>

                <input type="hidden" id="chatDateStart">
                <input type="hidden" id="chatDateEnd">

                <div class="input-group date">
                    <span><strong>Date:</strong></span>
                    <div id="chatSearchDate">Show all dates, click to change.</div>
                </div>

            </div> <!-- Search Box-->
        </div> <!-- .p-4 -->

        <!-- Start chat-message-list -->
        <div class="">

            <div class="chat-message-list px-2" data-simplebar>
                <ul class="list-unstyled chat-list chat-user-list" id="chatList"></ul>
            </div>
        </div>
        <!-- End chat-message-list -->
    </div>
    <!-- Start chats content -->
</div>
