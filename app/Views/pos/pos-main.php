<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

echo view('templates/myheader.php');
?>

<style>

.form-section{
    background:#fff;
    border-radius:10px;
    padding:20px;
    margin-bottom:20px;
    border:1px solid #e5e5e5;
}

.form-section h6{
    font-weight:600;
    margin-bottom:20px;
    color:#333;
    border-left:3px solid #dc3545;
    padding-left:10px;
}

.pos-card{
    border:1px solid #ddd;
    border-radius:10px;
    padding:20px;
    cursor:pointer;
    transition:.2s;
    height:100%;
}

.pos-card:hover{
    border-color:#dc3545;
    transform:translateY(-2px);
}

.pos-selected{
    border:2px solid #dc3545;
    background:#fff5f5;
}

.product-box{
    border:1px solid #ddd;
    border-radius:10px;
    padding:15px;
    text-align:center;
    cursor:pointer;
    transition:.2s;
    height:100%;
}

.product-box:hover{
    border-color:#dc3545;
    background:#fff5f5;
}

.cart-table td{
    vertical-align:middle;
}

.qty-btn{
    border:none;
    width:28px;
    height:28px;
    border-radius:5px;
    background:#f1f1f1;
}

.item-qty{
    width:55px;
    text-align:center;
}

</style>

<div class="me-pos-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>"/>

<div class="row mb-2 mt-5">
    <div class="col-12">

        <h4 class="fw-semibold my-3">Gym POS Module</h4>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>mydashboard">
                        <i class="ti ti-home fs-5"></i>
                    </a>
                </li>

                <li class="breadcrumb-item">POS</li>
                <li class="breadcrumb-item active">Cashiering</li>
            </ol>
        </nav>

    </div>
</div>

<form action="<?=site_url();?>pos" method="post" class="pos-reg-form" id="posRegForm">
    <div class="row">

        <!-- LEFT SIDE -->
        <div class="col-md-7">

            <!-- TRANSACTION TYPE -->
            <div class="form-section">
                <h6><i class="ti ti-cash me-2"></i> Transaction Type</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="pos-card pos-selected" onclick="selectPOSType('MEMBERSHIP', this)">
                            <h5 class="mb-2">Membership</h5>
                            <small>Monthly Subscription Payment</small>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="pos-card" onclick="selectPOSType('WALKIN', this)">
                            <h5 class="mb-2">Walk-In</h5>
                            <small>Daily Gym Access</small>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="pos-card" onclick="selectPOSType('ZUMBA', this)">
                            <h5 class="mb-2">Zumba</h5>
                            <small>Per Class Fee</small>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="pos-card" onclick="selectPOSType('CROSSFIT', this)">
                            <h5 class="mb-2">Crossfit</h5>
                            <small>Per Training Session</small>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="pos-card" onclick="selectPOSType('YOGA', this)">
                            <h5 class="mb-2">Yoga</h5>
                            <small>Per Class Fee</small>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <div class="pos-card" onclick="selectPOSType('ITEMS', this)">
                            <h5 class="mb-2">Items POS</h5>
                            <small>Supplements / Drinks / Apparel</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MEMBERSHIP -->
            <div class="form-section" id="membershipSection">
                <h6><i class="ti ti-user me-2"></i> Membership Payment</h6>
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label class="form-label">Select Member</label>
                        <select class="form-control" id="member_id">
                            <option value="">-- Select Member --</option>
                            <?php
                            $query = $this->db->query("
                                SELECT member_id, first_name, last_name
                                FROM tbl_members
                                ORDER BY last_name ASC
                            ");

                            foreach($query->getResultArray() as $row):
                            ?>

                            <option value="<?=$row['member_id'];?>">
                                <?=$row['last_name'];?>, <?=$row['first_name'];?>
                            </option>

                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Membership Plan</label>
                        <select class="form-control" id="membership_plan">
                            <option value="1000">1 Month - ₱1,000</option>
                            <option value="3000">3 Months - ₱3,000</option>
                            <option value="6000">6 Months - ₱6,000</option>
                            <option value="12000">12 Months - ₱12,000</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100" onclick="addMembershipCart()">
                            Add To Cart
                        </button>
                    </div>
                </div>
            </div>

            <!-- WALKIN -->
            <div class="form-section d-none" id="walkinSection">
                <h6><i class="ti ti-walk me-2"></i> Walk-In Customer</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="walkin_name">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" id="walkin_amount" value="100">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100" onclick="addWalkinCart()">
                            Add
                        </button>
                    </div>
                </div>
            </div>

            <!-- ZUMBA -->
            <div class="form-section d-none" id="zumbaSection">
                <h6><i class="ti ti-walk me-2"></i> Zumba Customer</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="zumba_name">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" id="zumba_amount" value="70">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100" onclick="addZumbaCart()">
                            Add
                        </button>
                    </div>
                </div>
            </div>

            <!-- CROSSFIT -->
            <div class="form-section d-none" id="crossfitSection">
                <h6><i class="ti ti-walk me-2"></i> Crossfit Customer</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="crossfit_name">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" id="crossfit_amount" value="500">
                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100" onclick="addCrossfitCart()">
                            Add
                        </button>
                    </div>
                </div>
            </div>

            
            <!-- YOGA -->
            <div class="form-section d-none" id="yogaSection">
                <h6><i class="ti ti-walk me-2"></i> Yoga Customer</h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" class="form-control" id="yoga_name">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Amount</label>
                        <input type="number" class="form-control" id="yoga_amount" value="100">
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-danger w-100" onclick="addYogaCart()">
                            Add
                        </button>
                    </div>
                </div>
            </div>

            <!-- ITEMS -->
            <div class="form-section d-none" id="itemsSection">
                <h6><i class="ti ti-shopping-cart me-2"></i> Items POS</h6>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <div class="product-box" onclick="addItem('Protein Shake','PRODUCT',120)">
                            <i class="ti ti-bottle fs-8"></i>
                            <h6 class="mt-2">Protein Shake</h6>
                            <div>₱120</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="product-box" onclick="addItem('Energy Drink','PRODUCT',90)">
                            <i class="ti ti-bottle fs-8"></i>
                            <h6 class="mt-2">Energy Drink</h6>
                            <div>₱90</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="product-box" onclick="addItem('Gym Towel','PRODUCT',250)">
                            <i class="ti ti-shirt fs-8"></i>
                            <h6 class="mt-2">Gym Towel</h6>
                            <div>₱250</div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="product-box" onclick="addItem('Whey Protein','PRODUCT',2500)">
                            <i class="ti ti-barbell fs-8"></i>
                            <h6 class="mt-2">Whey Protein</h6>
                            <div>₱2,500</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-md-5">
            <div class="form-section">
                <h6><i class="ti ti-receipt me-2"></i> POS Cart</h6>
                <div class="table-responsive">
                    <table class="table table-bordered cart-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Item Type</th>
                                <th width="120">Qty</th>
                                <th width="120">Amount</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="cartBody">
                        </tbody>
                    </table>
                </div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <strong>Subtotal</strong>
                    <strong id="subtotalText">₱0.00</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <strong>Total</strong>
                    <strong class="text-danger fs-5" id="grandtotalText">₱0.00</strong>
                </div>
                <div class="mb-3">
                    <label class="form-label">Payment Method</label>
                    <select class="form-control" id="paymentMethod">
                        <option>Cash</option>
                        <option>GCash</option>
                        <option>Maya</option>
                        <option>Card</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount Tendered</label>
                    <input type="number" class="form-control" id="amountTendered">
                </div>
                <div class="mb-3">
                    <label class="form-label">Change</label>
                    <input type="text" class="form-control" id="changeAmount" readonly>
                </div>
                <button type="submit" class="btn btn-danger w-100">
                    <i class="ti ti-cash-banknote me-1"></i>
                    Complete Payment
                </button>
            </div>
        </div>
    </div>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?=base_url('assets/js/pos/pos.js?v=1');?>"></script>

<script>

let cartCounter = 0;

function selectPOSType(type, element){

    $('.pos-card').removeClass('pos-selected');
    $(element).addClass('pos-selected');

    $('#membershipSection').addClass('d-none');
    $('#walkinSection').addClass('d-none');
    $('#itemsSection').addClass('d-none');
    $('#zumbaSection').addClass('d-none');
    $('#crossfitSection').addClass('d-none');
    $('#yogaSection').addClass('d-none');

    if(type == 'MEMBERSHIP'){
        $('#membershipSection').removeClass('d-none');
    }

    if(type == 'WALKIN'){
        $('#walkinSection').removeClass('d-none');
    }

    if(type == 'ITEMS'){
        $('#itemsSection').removeClass('d-none');
    }

    if(type == 'ZUMBA'){
        $('#zumbaSection').removeClass('d-none');
    }

    if(type == 'CROSSFIT'){
        $('#crossfitSection').removeClass('d-none');
    }

    if(type == 'YOGA'){
        $('#yogaSection').removeClass('d-none');
    }
}

function addMembershipCart(){

    let member = $('#member_id option:selected').text();
    let amount = parseFloat($('#membership_plan').val());

    if($('#member_id').val() == ''){
        toastr.error('Please select member');
        return;
    }

    member = member.replace(/\s+/g, ' ').trim();

    addCartRow(
        'Membership - ' + member, 'MEMBERSHIP',
        amount
    );
}

function addWalkinCart(){

    let name = $('#walkin_name').val();
    let amount = parseFloat($('#walkin_amount').val());

    if(name == ''){
        toastr.error('Please enter customer name');
        return;
    }

    name = name.replace(/\s+/g, ' ').trim();

    addCartRow(
        'Walk-In - ' + name, 'WALK-IN',
        amount
    );

    $('#walkin_name').val('');
}

function addCrossfitCart(){

    let name = $('#crossfit_name').val();
    let amount = parseFloat($('#crossfit_amount').val());

    if(name == ''){
        toastr.error('Please enter customer name');
        return;
    }

    name = name.replace(/\s+/g, ' ').trim();

    addCartRow(
        'Crossfit - ' + name, 'CROSSFIT',
        amount
    );

    $('#crossfit_name').val('');
}

function addZumbaCart(){

    let name = $('#zumba_name').val();
    let amount = parseFloat($('#zumba_amount').val());

    if(name == ''){
        toastr.error('Please enter customer name');
        return;
    }

    name = name.replace(/\s+/g, ' ').trim();

    addCartRow(
        'Zumba - ' + name, 'ZUMBA',
        amount
    );

    $('#zumba_name').val('');
}

function addYogaCart(){

    let name = $('#yoga_name').val();
    let amount = parseFloat($('#yoga_amount').val());

    if(name == ''){
        toastr.error('Please enter customer name');
        return;
    }

    name = name.replace(/\s+/g, ' ').trim();

    addCartRow(
        'Yoga - ' + name, 'YOGA',
        amount
    );

    $('#yoga_name').val('');
}

function addItem(itemName, itemType, amount){

    addCartRow(itemName, itemType, amount);
}

function addCartRow(itemName, itemType, amount){

    cartCounter++;

    $('#cartBody').append(`
        <tr id="row_${cartCounter}">

            <td>
                <p>${itemName}</p>
                <input type="hidden" class="item-name" value="${itemName}">
                <input type="hidden" class="item-price" value="${amount}">
            </td>

            <td>
                ${itemType}
                <input type="hidden" class="item-type" value="${itemType}">
            </td>

            <td>

                <div class="d-flex align-items-center gap-1">

                    <button type="button" class="qty-btn"
                        onclick="changeQty(${cartCounter}, -1)">
                        -
                    </button>

                    <input type="number"
                        min="1"
                        value="1"
                        class="form-control item-qty"
                        id="qty_${cartCounter}"
                        onchange="computeRow(${cartCounter})">

                    <button type="button" class="qty-btn"
                        onclick="changeQty(${cartCounter}, 1)">
                        +
                    </button>

                </div>

            </td>

            <td class="text-end">
                ₱<span id="amount_${cartCounter}">
                    ${amount.toFixed(2)}
                </span>
            </td>

            <td class="text-center">

                <button type="button"
                    class="btn btn-sm btn-danger px-2 py-0"
                    onclick="removeRow(${cartCounter})">
                    X
                </button>

            </td>

        </tr>
    `);

    computeTotals();
}

function changeQty(id, operation){

    let qtyField = $('#qty_' + id);

    let qty = parseInt(qtyField.val());

    qty += operation;

    if(qty <= 0){
        qty = 1;
    }

    qtyField.val(qty);

    computeRow(id);
}

function computeRow(id){

    let row = $('#row_' + id);

    let price = parseFloat(row.find('.item-price').val());

    let qty = parseInt($('#qty_' + id).val());

    let total = price * qty;

    $('#amount_' + id).text(total.toFixed(2));

    computeTotals();
}

function computeTotals(){

    let grandTotal = 0;

    $('#cartBody tr').each(function(){

        let rowId = $(this).attr('id').replace('row_', '');

        let amount = parseFloat(
            $('#amount_' + rowId).text()
        );

        grandTotal += amount;
    });

    $('#subtotalText').text('₱' + grandTotal.toFixed(2));
    $('#grandtotalText').text('₱' + grandTotal.toFixed(2));

    let tendered = parseFloat($('#amountTendered').val()) || 0;

    let change = tendered - grandTotal;

    $('#changeAmount').val(change.toFixed(2));
}

function removeRow(id){

    $('#row_' + id).remove();

    computeTotals();
}

$('#amountTendered').on('keyup change', function(){

    computeTotals();

});

</script>

<?php
echo view('templates/myfooter.php');
?>