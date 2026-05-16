var __CashReceipts = new __CashReceipts();

function __CashReceipts() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');

    this.__saveCashReceipt = function() { 
        'use strict';
        
        var forms = document.querySelectorAll('.cr-reg-form');

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

                    var date = $('#date').val();
                    var account_code = $('#account_code').val();
                    var amount = $('#amount').val();

                    var mparam = {
                        date: date,
                        account_code: account_code,
                        amount: amount,
                        meaction: 'SAVE'
                    };

                    jQuery.ajax({
                        type: "POST",
                        url: mesiteurl + 'cashreceipts',
                        context: document.body,
                        data: mparam,
                        global: false,
                        cache: false,
                        success: function(data) {
                            jQuery('.me-cashreceipt-msg').html(data);
                            toastr.success('Cash receipt saved successfully');
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
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
    __CashReceipts.__saveCashReceipt();
});