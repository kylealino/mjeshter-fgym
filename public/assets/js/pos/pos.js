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

                        var itemName  = clonedRow.find('.item-name').val();
                        var itemType  = clonedRow.find('.item-type').val();
                        var itemQty = clonedRow.find('.item-qty').val();
                        var itemPrice = clonedRow.find('.item-price').val();
                        
                        var cartrow = itemName + 'x|x' + itemType + 'x|x' + itemQty + 'x|x' + itemPrice;
                        cartdata.push(cartrow);
                    }

                    

                    // Get other form values
                    var transaction_type = $('#transaction_type').val();
                    var payment_method = $('#paymentMethod').val();
                    var amount_tendered = $('#amountTendered').val();
                    var change_amount = $('#changeAmount').val();
                    var grand_total = $('#grandtotalText').text().replace('₱', '');

                    //MEMBERSHIP
                    var member_id = $('#member_id').val();
                    let plan = $('#membership_plan option:selected').data('plan');
                    var membership_start_date = $('#membership_start_date').val();
                    var membership_end_date = $('#membership_end_date').val();
                    var membership_status = $('#membership_status').val();
                
                    if (cartdata.length === 0) {
                        toastr.error('Cart is empty!');
                        return;
                    }

                    if (!amount_tendered) {
                        toastr.error('Amount tendered is required!');
                        return;
                    }

                    if (amount_tendered + 1 < grand_total) {
                        toastr.error('Luge ka diyan idol!');
                        return;
                    }
 
                    var mparam = {
                        cartdata: cartdata,
                        payment_method: payment_method,
                        amount_tendered: amount_tendered,
                        change_amount: change_amount,
                        grand_total: grand_total,
                        member_id: member_id,
                        plan: plan,
                        membership_start_date: membership_start_date,
                        membership_end_date: membership_end_date,
                        membership_status: membership_status,
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
                            return;
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
