$('#reload').click(function() {
    location.reload();
});

$('#department_select').change(function() {
    writeOrders($('#department_select').val());
});

$('#clear_filters').click(function() {
    $('.filter_input').val('');
    $("#order_table > tbody > tr").attr('hidden', false);
});

$('.filter_input').keyup(function() {
    $('.filter_input').not($(this)).val('');
    filterOrderTable(this);
    if (filtersClear()) {
        $('#process_selected').attr('disabled', false);
    } else {
        $('#process_selected').attr('disabled', true);
    }
});

function filterOrderTable(filterBox) {
    var filterString = $(filterBox).val().toLowerCase();
    var filterCell = $(filterBox).attr('id').substring(7);
    $("#order_table > tbody > tr").not(":first").each(function() {
        $(this).attr('hidden', false);
        var orderNumber = $(this).find('.' + filterCell + '_cell').html().toLowerCase();
        if (orderNumber.indexOf(filterString) == -1 ) {
            $(this).attr('hidden', true);
        }
    });
}

function filtersClear() {
    var clear = true;
    $('.filter_input').each(function() {
        if ($(this).val().length !== 0) {
            clear = false;
        }
    });
    return clear;
}

$('#process_selected').click(function() {
    var ordersToProcess = [];
    $("#order_table tr").each(function() {
        if ($(this).find('.select_checkbox').prop("checked") === true) {
            ordersToProcess.push($(this).find('.order_number_cell').html());
        }
    });
    console.log(ordersToProcess);
});

function writeOrders(department) {
    $("#order_table tr").find("tr:gt(0)").remove(); //Clear table
    for (var i=0; i < openOrders.length; i++) {
        if (openOrders[i].department == department) {
            writeOrderRow(openOrders[i]);
        }
    }
}

function writeOrderRow(order) {
    var new_row = $(getOrderRow(order));
    $('#order_table').append(new_row);
    new_row.find('.process_button').click(processButtonGenerator(order.guid));
    new_row.find('.select_checkbox').change(selectCheckboxGenerator());
}

function processButtonGenerator(guid) {
    return function(event) {
        console.log(guid);
        var row = $($(this).closest("tr"));
        row.css('background-color', '#4F814F');
        row.css('color', '#444444');
        var checkbox = row.find('.select_checkbox');
        checkbox.toggle();
        checkbox.attr('disabled', true);
        $(this).attr('disabled', true);
    };
}

function selectCheckboxGenerator() {
    return function(event) {
        var row = $($(this).closest("tr"));
        if ($(this).prop("checked") === false) {
            row.css('background-color', '#DDDDDD');
            row.css('color', '#444444');
        } else {
            row.removeAttr( 'style' );
        }
    };
}

function getOrderRow(order) {
    var rowString= '<tr class="order_table_row">';
    rowString += '<td class="process_button_cell"><button class="process_button">Process</button></td>';
    rowString += '<td class="select_checkbox_cell"><input class="select_checkbox" type="checkbox" checked /></td>';
    rowString += '<td class="order_number_cell">' + order.order_number + '</td>';
    rowString += '<td class="customer_name_cell">' + order.customer_name + '</td>';
    rowString += '<td class="item_table_cell"><table class="item_table">';
    for (var i=0; i < order.items.length; i++) {
        rowString += '<tr class="item_table_row">';
        rowString += '<td class="item_sku_cell">' + order.items[i].sku + '</td>';
        rowString += '<td class="item_title_cell">' + order.items[i].item_title + '</td></tr>';
    }
    rowString += '</table></td></tr>';
    return rowString;
}
