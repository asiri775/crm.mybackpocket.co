$(document).on("click", ".open-main-menu", function () {
    if (!document.body.classList.contains("show-menu")) {
        document.body.classList.add("show-menu");
    }
});

$(document).on("click", ".close-main-menu", function () {
    if (document.body.classList.contains("show-menu")) {
        document.body.classList.remove("show-menu");
    }
});

function enableAllNumberInputMasks() {
    const maskTemplate = "+1 (999) 999-9999";
    const inputs = document.getElementsByTagName("input");
    for (const input of inputs) {
        if (!input.hasAttribute("data-mask")) {
            if (input.hasAttribute("name")) {
                const name = input.getAttribute("name");
                if (name.toString().includes("contact_numbers")) {
                    input.setAttribute("data-mask", maskTemplate);
                    Inputmask(maskTemplate).mask(input);
                }
            }
        }
    }
}

// Callback function for observer
function handleDomChange(mutationsList, observer) {
    setTimeout(() => {
        enableAllNumberInputMasks();
    }, 1000);
}

// Create a MutationObserver instance
const observer = new MutationObserver(handleDomChange);
// Configuration
const config = { childList: true, subtree: true, attributes: true };
// Wait for the target node to exist and observe it
const targetNode = document.getElementById("app");
if (targetNode) {
    observer.observe(targetNode, config);
}

$(document).ready(function () {

    $(document).on("keyup", "#search-chat-user", function () {
        $(".search-u-names-chat").addClass("show");
        $(".search-results").html('<div class="loading">Loading</div>');
        const searchQuery = $(this).val().trim();
        $.get(SEARCH_URL_API, { query: searchQuery }, function (result) {
            if (result.status === "success") {
                const items = result.items;
                if (items.length > 0) {
                    let data = "";
                    for (const item of items) {
                        data += '<a href="' + item.link + '">' + item.name + '</a>';
                    }
                    $(".search-results").html(data);
                } else {
                    $(".search-results").html('<div class="no-error">No Results Found</div>');
                }
            } else if (result.status === "error") {
                $(".search-u-names-chat").removeClass("show");
            }
        });
    });

    $(document).on('click', function (event) {
        if (!$(event.target).closest('.search-u-names-chat').length) {
            $(".search-u-names-chat").removeClass("show");
        }
    });

    // if ($(".project-form").length > 0) {
    //     function openClientSearch() {
    //         const mainElement = $('input[name="client_id"]');
    //         mainElement.parent().find(".inline-block").click();
    //         setTimeout(() => {
    //             mainElement.find('input[type="text"]').focus();
    //         }, 100);
    //     }

    //     document.querySelector('.project-form input[name="name"]').addEventListener('keydown', function (event) {
    //         if (event.key === "Tab" && !event.shiftKey) {
    //             event.preventDefault();
    //             openClientSearch();
    //         }
    //     });
    //     document.querySelector('.project-form input[name="start_date"]').nextSibling.addEventListener('keydown', function (event) {
    //         if (event.key === "Tab" && event.shiftKey) {
    //             event.preventDefault();
    //             openClientSearch();
    //         }
    //     });
    // }


});