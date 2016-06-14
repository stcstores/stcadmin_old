<?php
require_once(dirname($_SERVER['DOCUMENT_ROOT']) . '/private/config.php');
require_once($CONFIG['include']);
STCAdmin\UserLogin::checkLogin();
require_once($CONFIG['header']);
?>

<style>
    .download_image {
        display: inline-block;
        margin: 1em;
        padding: 1em;
        text-align: center;
        background: #444;
        border-radius: 0.5em;
    }

    .download_image p {
        color: white;
        padding: 0;
        margin: 0;
    }

    .download_image button {
        margin: 0 1em 0 1em;
    }

    #image_preview {
        display: none;
        position: fixed; /* could be absolute */
        top: 0; left: 0; bottom: 0; right: 0;
        margin: auto;
        width: 100%;
        height: 100%;
        background: #444;
        text-align: center;
        vertical-align: middle;
    }

    .image_preview_img {
        margin-top: 2.5%;
        display: inline-block;
        vertical-align: middle;
        height: 90%;
        width: 90%;
        background-position: center center;
        background-size: contain;
        background-repeat: no-repeat;

    }
</style>
<?php

$departments = glob('*', GLOB_ONLYDIR);
echo "<label for='department_select'>Department: </label>";
echo "<select id='department_select' name='department_select'>";
echo "<option></option>";
foreach ($departments as $department) {
    $department_name = ucwords(str_replace('_', ' ', $department));
    echo "<option value='{$department}'>{$department_name}</option>";
}
echo "</select>";

echo "<div id='image_list'></div>";
$image_extensions = ['jpg', 'png'];

echo "<div id='image_preview'><div class='image_preview_img'></div></div>";
?>

<script>
    $(window).load(function() {
        $('#image_preview').click(function() {
            $('#image_preview').css('display', 'none');
        })
        $('#department_select').change(function() {
            $('#image_list').html('');
            var department = $('#department_select').val()
            var data = {'department': department};
            $.post('get_image_list.php', data, function (images) {
                for (var i=0; i<images.length; i++) {
                    var filename = images[i];
                    var path = department + '/' + filename;
                    var name = images[i].split('.')[0];
                    var image_string = "<div class='download_image'><img class='image_thumb' src='" + path + "' alt='" + filename + "' width='150'/><p>" + name + "</p><br /><a download='" + filename + "' href='" + path + "'><button class='image_download'><img src='/images/download_icon.png' alt='download' width='20'/></button></a><button class='image_delete'><img src='/images/delete_icon.png' alt='download' width='20'/></button></div>"
                    $('#image_list').append(image_string);
                    $('.image_delete:last').click(delete_button_generator(department, filename));
                    $('.image_thumb:last').click(image_thumb_generator(path));
                }
            }, 'json');
        });
    });

    function image_thumb_generator(path) {
        return function(event){
            $('#image_preview').css('display', 'inline-block');
            $('.image_preview_img').css('background-image', 'url("' + path + '")')
        }
    }

    function delete_button_generator(department, filename) {
        return function(event) {
            $.post('delete_image.php', {'department': department, 'filename': filename}, function(response) {
                console.log(response);
                $('#department_select').trigger("change");
            })
        }
    }
</script>
