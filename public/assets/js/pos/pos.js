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

                    for (var aa = 0; aa < rowcount; aa++) {
                        var clonedRow = jQuery('#cartBody tr:eq(' + aa + ')');
                        
                        // USE THE HIDDEN FIELDS - No \n issues!
                        var itemName = clonedRow.find('.item-name').val();  // "Protein Shake"
                        var itemPrice = clonedRow.find('.item-price').val(); // "120"
                        var itemType = clonedRow.find('.item-type').val();   // "PRODUCT"
                        var qty = clonedRow.find('.qty-input').val();        // "1"
                        var amount = clonedRow.find('.item-amount').val();   // "120.00"
                        
                        cartrow = itemName + 'x|x' + itemPrice + 'x|x' + itemType + 'x|x' + qty + 'x|x' + amount;
                        cartdata.push(cartrow);
                    }
                    console.log(cartdata);

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