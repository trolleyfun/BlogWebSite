/* Summernote Editor */
$(document).ready(function() {
    $('.summernote-post').summernote({
        height: 200,
        lang: 'ru-RU'
    });

    $('.summernote-comment').summernote({
      height: 100,
      lang: 'ru-RU'
    });
});

/* Select All CheckBox Items */
$(document).ready(function() {
    $('#allCheckBoxes').click(function(e) {
        var status = this.checked;  
        $('.checkBoxes').each(function() {
          this.checked = status;
        });
    });

    $('.checkBoxes').click(function(e) {
        var status = this.checked;
        if (!status) {
            $('#allCheckBoxes').each(function() {
                this.checked = false;
            });
        }
    });
});

/* Display confirm message when user select delete option */
function confirmDeleteOptionTop(message) {
    var selected_option = document.querySelector('#select-option-top').value;

    if (selected_option === 'delete') {
        return confirm(message);
    } else {
        return true;
    }
}

function confirmDeleteOptionBottom(message) {
    var selected_option = document.querySelector('#select-option-bottom').value;

    if (selected_option === 'delete') {
        return confirm(message);
    } else {
        return true;
    }
}

/* Instant counting users online. Display the result in element with class "users_online" */
function showUsersOnlineCnt() {
    $.get("includes/users_online_cnt.php", (data)=>{
        $('.users_online').text(data);
    });
}

showUsersOnlineCnt();
setInterval(showUsersOnlineCnt, 1000); //every 1000ms