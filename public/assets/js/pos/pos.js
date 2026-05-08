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
                    // SHOW PRELOADER
                    // =========================
                    $('.preloader').css('display','flex').hide().fadeIn(200);

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
                
                    console.log(plan);

                    if (cartdata.length === 0) {
                        $('.preloader').fadeOut();
                        toastr.error('Cart is empty!');
                        return;
                    }

                    if (!amount_tendered) {
                        $('.preloader').fadeOut();
                        toastr.error('Amount tendered is required!');
                        return;
                    }

                    if (amount_tendered + 1 < grand_total) {
                        $('.preloader').fadeOut();
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
                        data: mparam,
                        global: false,
                        cache: false,
                        success: function(data) {

                            // =========================
                            // HIDE PRELOADER
                            // =========================
                            $('.preloader').fadeOut(200);

                            jQuery('.me-pos-msg').html(data);
                            toastr.success('POS saved successfully');
                            return;
                        },
                        error: function(xhr, status, error) {

                            // =========================
                            // HIDE PRELOADER
                            // =========================
                            $('.preloader').fadeOut(200);

                            alert('Error: ' + error);
                            return false;
                        }
                    });

                } catch(err) {

                    // =========================
                    // HIDE PRELOADER
                    // =========================
                    $('.preloader').fadeOut();

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
