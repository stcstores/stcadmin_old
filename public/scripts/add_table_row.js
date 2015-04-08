function addRow(row) {    
    //alert("TEST");
    
    $("#variation_table").append(row);
}

function deleteRow(rowNumber) {
    var row = "#var_row_" + rowNumber;
    $(row).remove();
}