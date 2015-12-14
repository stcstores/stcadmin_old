$('#reload').click(function() {
    location.reload();
});

$('#department_select').change(function() {
    writeOrders($('#department_select').val())
});

function writeOrders(department) {
    $("#order_table").find("tr:gt(0)").remove(); //Clear table
    for (var i=0; i < openOrders.length; i++) {
        if (openOrders[i].department == department) {
            writeOrderRow(openOrders[i]);
        }
    }
}

function writeOrderRow(order) {
    $('#order_table').append(getOrderRow(order));
}

function getOrderRow(order) {
    var rowString= "<tr><td><button>Process</button></td><td><input type='checkbox' checked /></td><td>" + order.order_number + "</td><td>" + order.customer_name + "</td>";
    rowString += "<td><table class='item_table'>";
    for (var i=0; i < order.items.length; i++) {
        rowString += "<tr><td>" + order.items[i].sku + "</td>";
        rowString += "<td>" + order.items[i].item_title + "</td></tr>";
    }
    rowString += "</table></td></tr>";
    return rowString;
}
