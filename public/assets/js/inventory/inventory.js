var __Inventory = new __Inventory();

function __Inventory() {  
    const mesiteurl = $('#__siteurl').attr('data-mesiteurl');
    let processing = false;

    this.__saveInventory = function() { 
        'use strict';
        
        var forms = document.querySelectorAll('.inv-reg-form');

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

                    // var supplier = $('#supplier').val();
                    var product_name = $('#product_name').val();
                    var category = $('#category').val();
                    var purchase_price = $('#purchase_price').val();
                    var selling_price = $('#selling_price').val();
                    var stock_qty = $('#stock_qty').val();

                    // console.log(supplier);
                    console.log(product_name);
                    console.log(category);
                    console.log(purchase_price);
                    console.log(selling_price);
                    console.log(stock_qty);

                    var mparam = {
                        // supplier: supplier,
                        product_name: product_name,
                        category: category,
                        purchase_price: purchase_price,
                        selling_price: selling_price,
                        stock_qty: stock_qty,
                        meaction: 'STOCKIN-SAVE'
                    };

                    
                    // =========================
                    // AJAX - LIKE YOUR BUDGET CODE
                    // =========================
                    jQuery.ajax({
                        type: "POST",
                        url: mesiteurl + 'inventory',
                        context: document.body,
                        data: mparam,  // Note: your budget code uses eval(mparam) but that's not needed
                        global: false,
                        cache: false,
                        success: function(data) {
                            jQuery('.me-inventory-msg').html(data);
                            toastr.success('Product saved successfully');
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

    this.__saveAdjustment = function() { 
        'use strict';
        
        var forms = document.querySelectorAll('.adj-reg-form');

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

                    var adj_product_name = $('#adj_product_name').val();
                    var adj_type = $('#adj_type').val();
                    var remarks = $('#remarks').val();
                    var adj_qty = $('#adj_qty').val();

                    console.log(adj_product_name);
                    console.log(adj_type);
                    console.log(remarks);
                    console.log(adj_qty);

                    var mparam = {
                        adj_product_name: adj_product_name,
                        adj_type: adj_type,
                        remarks: remarks,
                        adj_qty: adj_qty,
                        meaction: 'ADJUSTMENT-SAVE'
                    };

                    
                    // =========================
                    // AJAX - LIKE YOUR BUDGET CODE
                    // =========================
                    jQuery.ajax({
                        type: "POST",
                        url: mesiteurl + 'inventory',
                        context: document.body,
                        data: mparam,  // Note: your budget code uses eval(mparam) but that's not needed
                        global: false,
                        cache: false,
                        success: function(data) {
                            jQuery('.me-adjustment-msg').html(data);
                            toastr.success('Product saved successfully');
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
    __Inventory.__saveInventory();
    __Inventory.__saveAdjustment();
});
