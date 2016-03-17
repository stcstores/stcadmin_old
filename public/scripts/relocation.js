$(document).ready(function() {
    $('.update_button').click(function() {
        var update_data = [];
        $('.item_row').each(function() {
            var item_id = $(this).find('.binrack_value').attr('id');
            //console.log('stock_id: ' + item_id);
            var binrack_value = $(this).find('.binrack_value').val();
            //console.log('new value: ' + binrack_value);
            var title = $(this).find('input.item_title').val();
            //console.log('title: ' + title);
            update_data.push({'item_id': item_id, 'binrack_value': binrack_value, 'title': title});
        });
        $('#item_rows').html('');
        console.log(update_data);
        $.post('update_location.php', {
            'location_update': JSON.stringify(update_data)
        }, function(data) {
            $('#item_search').submit();
        });
    });
    $('#item_search').submit(function(e) {
        e.preventDefault();
        var data = $('#item_search').serialize();
        $.post("relocation_search.php", data, function(response) {
            $('#item_rows').html(response);
        });
        return false;
    });
});
