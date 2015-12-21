$('#reload').click(function() {
    location.reload();
});

$('#department_select').change(function() {
    writeOrders($('#department_select').val());
    updateOrderCounts();
});

$('#clear_filters').click(function() {
    $('.filter_input').val('');
    $("#order_table > tbody > tr").attr('hidden', false);
    $('#process_selected').removeAttr('disabled');
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

$('#process_selected').click(function() {
    var ordersToProcess = [];
    $("#order_table tr").each(function() {
        if ($(this).find('.select_checkbox').prop("checked") === true) {
            ordersToProcess.push($(this).find('.order_number_cell').html());
        }
    });
    if (confirm("Are you sure you want to process " + ordersToProcess.length + " orders?")) {
        console.log(ordersToProcess);
        for (var i=0; i < ordersToProcess.length; i++) {
            processOrder(ordersToProcess[i]);
        }
        updateOrderCounts();
    }
});

$('#toggle_button').click(function() {
    var checked = $('#order_table tr:nth-child(2)').find('.select_checkbox').prop("checked");
    var checkbox;
    $("#order_table tr").each(function() {
        if ($(this).is(":visible")) {
             checkbox = $(this).find('.select_checkbox');
             if (checked) {
                 checkbox.click();
             } else {
                 checkbox.click();
             }
         }
    });
});

function filterOrderTable(filterBox) {
    var filterString = $(filterBox).val().toLowerCase();
    var filterCell = $(filterBox).attr('id').substring(7);
    $("#order_table > tbody > tr").each(function() {
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

function writeOrders(department) {
    $("#order_table").find("tr:gt(0)").remove(); //Clear table
    for (var i=0; i < openOrders.length; i++) {
        if (openOrders[i].department === department) {
            writeOrderRow(openOrders[i]);
        }
    }
    $('#order_table').tablesorter({
        headers: {
            0: {sorter: false},
            1: {sorter: false}
        },
        sortList: [[3,1]]
    });
    $('#order_table').removeAttr('style');
}

function countOrders() {
    var orderCount = 0;
    var department = $("#department_select").val();
    for (var i=0; i<openOrders.length; i++) {
        if (openOrders[i].department == department) {
            orderCount ++;
        }
    }
    return orderCount;
}

function countSelectedOrders() {
    var selectedOrdersCount = 0;
    $("#order_table tr").each(function() {
        if ($(this).find('.select_checkbox').prop("checked") === true) {
            selectedOrdersCount ++;
        }
    });
    return selectedOrdersCount;
}

function updateOrderCounts() {
    $('#selected_count').html(countSelectedOrders());
    $('#order_count').html(countOrders());
}

function writeOrderRow(order) {
    var new_row = $(getOrderRow(order));
    $('#order_table').append(new_row);
    new_row.find('.process_button').click(processButtonGenerator(order.order_number));
    new_row.find('.select_checkbox').change(selectCheckboxGenerator());
}

function processButtonGenerator(orderNumber) {
    return function(event) {
        console.log(orderNumber);
        processOrder(orderNumber);
        $(this).attr('disabled', true);
    };
}

function markProcessed(orderNumber) {
    $("#order_table > tbody > tr").each(function() {
        if ($(this).find('.order_number_cell').html() == orderNumber) {
            var row = $(this);
            row.css('background-color', '#4F814F');
            row.css('color', '#444444');
            var checkbox = row.find('.select_checkbox');
            checkbox.attr('checked', false);
            checkbox.attr('disabled', true);
        }
    });
}

function markProcessedFailed(orderNumber) {
    $("#order_table > tbody > tr").each(function() {
        if ($(this).find('.order_number_cell').html() == orderNumber) {
            var row = $(this);
            row.css('background-color', 'red');
            row.css('color', '#444444');
            var checkbox = row.find('.select_checkbox');
            checkbox.attr('checked', false);
            checkbox.attr('disabled', true);
        }
    });
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
        updateOrderCounts();
    };
}

function getOrderRow(order) {
    var rowString= '<tr class="order_table_row">';
    rowString += '<td class="process_button_cell"><button class="process_button">Process</button></td>';
    rowString += '<td class="select_checkbox_cell"><input class="select_checkbox" type="checkbox" checked /></td>';
    rowString += '<td class="order_number_cell">' + order.order_number + '</td>';
    rowString += '<td class="order_date_cell">' + order.date_recieved + ' ' + order.time_recieved.substr(0, 5) + '</td>';
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

function processOrder(orderNumber) {
    $.post('process_order.php', {'order_number': orderNumber}, function(result) {
        orderProcessedCheck(orderNumber);
    });
}

function orderProcessedCheck(orderNumber) {
    $.post('order_is_processed.php', {'order_number': orderNumber}, function(result) {
        if (result === 'success') {
            markProcessed(orderNumber);
        } else {
            markProcessedFailed(orderNumber);
        }
    });
}
