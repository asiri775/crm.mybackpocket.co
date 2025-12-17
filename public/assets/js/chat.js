window.lastMessageTime = null;

window.onload = function () {
    // Wait for a short delay before setting the event listener
    setTimeout(function () {
        // Initialize chats on page load
        loadChats('', true);

        // Attach the 'keyup' event listener to the search input
        document.getElementById("chatSearch").addEventListener("keyup", function (event) {
            const searchText = event.target.value;
            loadChats(searchText, true);  // Fetch chats with the query string
        });

        const chatMessageInput = document.getElementById("chatMessageInput");
        if (chatMessageInput != null) {
            //load chat messages
            loadMessages();
            // Attach the 'keyup' event listener to the search input
            document.getElementById("sendMessage").addEventListener("click", function (event) {
                event.preventDefault();
                sendMessgae();
            });
            //handle enter clicked
            document.getElementById("chatMessageInput").addEventListener("keydown", function (event) {
                if (event.key === "Enter") {
                    event.preventDefault(); // Prevent the default behavior (like submitting a form)
                    sendMessgae();
                }
            });
        }

    }, 0);  // Set a minimal delay (0ms)
};

function scrollToBottom() {
    const ulElement = document.getElementById("chatMessagesListMain");
    ulElement.scrollTop = ulElement.scrollHeight;
}

function initChatDateFilter() {
    const startDate = moment().startOf('month').format("MM/DD/YYYY");
    const endDate = moment().format("MM/DD/YYYY");
    $('#chatSearchDate').daterangepicker({
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        "startDate": startDate,
        "endDate": endDate
    }, function (start, end, label) {
        $("#chatDateStart").val(start.format('YYYY-MM-DD'));
        $("#chatDateEnd").val(end.format('YYYY-MM-DD'));
        $("#chatSearchDate").html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        const searchText = $("#chatSearch").val();
        loadChats(searchText, true);
    });
}

function makeHtmlMessageElement(chatMessage) {
    if (chatMessage.is_system_message.toString() === "1") {
        return `<li>
        <div class="chat-day-title">
            <span class="title">${chatMessage.message}</span>
        </div>
    </li>`;
    } else {
        return `<li class="${chatMessage.is_my_message.toString() === "true" ? "right" : ""}">
            <div class="conversation-list">
                <div class="chat-avatar">
                    <img
                        src="${chatMessage.sender.image}">
                </div>
                <div class="user-chat-content">
                    <div class="ctext-wrap">
                        <div class="ctext-wrap-content">
                            <p class="mb-0">
                            ${chatMessage.message}
                            </p>
                            <p class="chat-time mb-0"><i class="ri-time-line align-middle"></i>
                                <span class="align-middle" title="${chatMessage.created_at}">${chatMessage.time}</span>
                            </p>
                        </div>
                        <div class="dropdown align-self-start">
                            <a class="dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                <i class="ri-more-2-fill"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="#">Copy <i
                                        class="ri-file-copy-line float-end text-muted"></i></a>
                                <a class="dropdown-item" href="#">Save <i
                                        class="ri-save-line float-end text-muted"></i></a>
                                <a class="dropdown-item" href="#">Forward <i
                                        class="ri-chat-forward-line float-end text-muted"></i></a>
                                <a class="dropdown-item" href="#">Delete <i
                                        class="ri-delete-bin-line float-end text-muted"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="conversation-name">${chatMessage.sender.name}</div>
                </div>
            </div>
        </li>`;
    }
}

function markChatRead(lastMessageId) {
    // Build the URL with query parameters using URLSearchParams
    const url = new URL(CHAT_READ_API_URL);
    const params = new URLSearchParams();
    params.append('last_message_id', lastMessageId);
    url.search = params.toString();

    // Fetch the chats from the API
    fetch(url)
        .then(response => response.json())
        .then(data => {
            //done
            loadChats();
        })
        .catch(error => {
            //error
        });
}

function loadMessages() {
    const chatsElement = document.getElementById("chatMessagesList");
    chatsElement.innerHTML = `<li class="loading">Loading Messages...</li>`;

    // Build the URL with query parameters using URLSearchParams
    const url = new URL(MESSAGES_API_URL);
    const params = new URLSearchParams();
    params.append('last_message_time', window.lastMessageTime ?? "");
    url.search = params.toString();

    // Fetch the chats from the API
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const chatsElement = document.getElementById("chatMessagesList");
            if (data.error) {
                alert(data.error);
                chatsElement.innerHTML = `<li class="loading">Failed to Load Messages</li>`;
            } else {
                const chats = data.data;
                if (chats.length > 0) {
                    let firstTimeMessages = window.lastMessageTime == null ? true : false;
                    let chatHtml = "";
                    let lastMessage = null;
                    for (const chat of chats) {
                        chatHtml += makeHtmlMessageElement(chat);
                        lastMessage = chat;
                    }
                    chatsElement.innerHTML = chatHtml;
                    if (firstTimeMessages) {
                        scrollToBottom();
                        markChatRead(lastMessage.id);
                    }
                } else {
                    chatsElement.innerHTML = `<li class="loading">No Messages found</li>`;
                }
            }
        })
        .catch(error => {
            console.error('Error during fetch:', error);
            chatsElement.innerHTML = `<li class="loading">Failed to Load</li>`;
        });
}

function sendMessgae() {
    const sendMsgBtn = document.getElementById("sendMessage");
    const chatMessageInput = document.getElementById("chatMessageInput")
    const chatMessage = chatMessageInput.value.toString().trim();
    if (chatMessage != "") {
        sendMsgBtn.setAttribute("disabled", true);
        sendMsgBtn.innerHTML = "Sending...";
        chatMessageInput.value = "";

        const csrfToken = document.querySelector('#chatMsgBox input[name="_token"]').value;

        // Create a FormData object from the form
        const formData = new FormData();
        // Add the CSRF token to the FormData (if necessary)
        formData.append('message', chatMessage);

        fetch(SEND_MESSAGE_API_URL, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    const chatsElement = document.getElementById("chatMessagesList");
                    chatsElement.innerHTML = chatsElement.innerHTML + makeHtmlMessageElement(data.data);
                    scrollToBottom();
                    loadChats();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            }).finally(() => {
                sendMsgBtn.removeAttribute("disabled");
                sendMsgBtn.innerHTML = "Send";
            });

    } else {
        alert("Message is required");
    }
}

function loadChats(query = '', showLoading = false) {
    const chatsElement = document.getElementById("chatList");
    if (showLoading) {
        chatsElement.innerHTML = `<li class="loading">Loading Chats...</li>`;
    }

    // Build the URL with query parameters using URLSearchParams
    const url = new URL(SEARCH_CHAT_API_URL);
    const params = new URLSearchParams();
    if (query) {
        params.append('search', query);
    }
    params.append('start', $("#chatDateStart").val());
    params.append('end', $("#chatDateEnd").val());
    url.search = params.toString();

    // Fetch the chats from the API
    fetch(url)
        .then(response => response.json())
        .then(data => {
            const chatsElement = document.getElementById("chatList");
            if (data.error) {
                alert(data.error);
                chatsElement.innerHTML = `<li class="loading">Failed to Load</li>`;
            } else {
                const chats = data.data;
                if (chats.length > 0) {
                    let chatHtml = "";
                    for (const chat of chats) {
                        chatHtml += `<li class="unread">
                            <a href="${chat.link}">
                                <div class="d-flex">
                                    <div class="chat-user-img away align-self-center me-3 ms-0">
                                        <img src="${chat.image}" class="rounded-circle avatar-xs" alt="${chat.name}">
                                        <span class="user-status"></span>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-truncate font-size-15 mb-1">${chat.name}</h5>
                                        <p class="chat-user-message text-truncate mb-0">${chat.last_message}</p>
                                    </div>
                                    <div class="font-size-11" title="${chat.last_message_at}">${chat.time}</div>
                                    <div class="unread-message">
                                        ${chat.unread_messages > 0 ? `<span class="badge badge-soft-danger rounded-pill">${chat.unread_messages}</span>` : ''}
                                    </div>
                                </div>
                            </a>
                        </li>`;
                    }
                    chatsElement.innerHTML = chatHtml;
                } else {
                    chatsElement.innerHTML = `<li class="loading">No chats available</li>`;
                }
                initChatDateFilter();
            }
        })
        .catch(error => {
            console.error('Error during fetch:', error);
            chatsElement.innerHTML = `<li class="loading">Failed to Load</li>`;
            initChatDateFilter();
        });
}
