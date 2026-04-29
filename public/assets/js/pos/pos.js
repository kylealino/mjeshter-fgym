var __POS = new __POS();

function __POS() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');
    let processing = false;

    this.__savePOS = function() { 
        'use strict';
        
        var forms = document.querySelectorAll('.pos-reg-form');

        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    return;
                }
                
                try {
                    event.preventDefault();
                    event.stopPropagation();

                    // =========================
                    // GET CART DATA - LIKE YOUR BUDGET CODE
                    // =========================
                    var rowcount = jQuery('#cartBody tr').length;
                    var cartdata = [];
                    var cartrow = '';

                    for (var aa = 0; aa < rowcount; aa++) {  // Start from 0 since no header in tbody
                        var clonedRow = jQuery('#cartBody tr:eq(' + aa + ')');
                        
                        // Get values using your budget code pattern
                        var itemName = clonedRow.find('td:eq(0)').clone().children().remove().end().text().trim();
                        var itemPrice = clonedRow.find('.item-price').val();
                        var itemType = clonedRow.find('.item-type').val();
                        var qty = clonedRow.find('.qty-input').val();
                        var amount = clonedRow.find('#amount_' + clonedRow.attr('id').replace('row_', '')).text();
                        
                        // Create delimited string like your budget code
                        cartrow = itemName + 'x|x' + itemPrice + 'x|x' + itemType + 'x|x' + qty + 'x|x' + amount;
                        console.log(itemName);
                        console.log(itemType);
                        console.log(qty);
                        console.log(amount);
                        cartdata.push(cartrow);
                    }

                    // Get other form values
                    var transaction_type = $('#transaction_type').val();
                    var payment_method = $('select:contains("Payment Method")').val(); // Adjust selector as needed
                    var amount_tendered = $('#amountTendered').val();
                    var change_amount = $('#changeAmount').val();
                    var grand_total = $('#grandtotalText').text().replace('₱', '');

                    // =========================
                    // PARAMS - LIKE YOUR BUDGET CODE
                    // =========================
                    var mparam = {
                        cartdata: cartdata,
                        transaction_type: transaction_type,
                        payment_method: payment_method,
                        amount_tendered: amount_tendered,
                        change_amount: change_amount,
                        grand_total: grand_total,
                        meaction: 'POS-SAVE'
                    };

                    
                    // =========================
                    // AJAX - LIKE YOUR BUDGET CODE
                    // =========================
                    jQuery.ajax({
                        type: "POST",
                        url: mesiteurl + 'pos',
                        context: document.body,
                        data: mparam,  // Note: your budget code uses eval(mparam) but that's not needed
                        global: false,
                        cache: false,
                        success: function(data) {
                            jQuery('.me-pos-msg').html(data);
                            toastr.success('POS saved successfully');
                        },
                        error: function(xhr, status, error) {
                            alert('Error: ' + error);
                            return false;
                        }
                    });

                } catch(err) {
                    alert(err.message);
                    return false;
                }
            }, false);
        });
    };
}

$(document).ready(function() {
    __POS.__savePOS();
});