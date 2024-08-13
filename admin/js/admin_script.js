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