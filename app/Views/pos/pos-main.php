<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

echo view('templates/myheader.php');
?>

<style>
    :root {
        --primary: #1e3a5f;
        --primary-dark: #0f2b44;
        --primary-light: #2c5a8c;
        --danger: #dc2626;
        --danger-dark: #b91c1c;
        --success: #10b981;
        --gray-50: #f8fafc;
        --gray-100: #f1f5f9;
        --gray-200: #e2e8f0;
        --gray-300: #cbd5e1;
        --gray-400: #94a3b8;
        --gray-500: #64748b;
        --gray-600: #475569;
        --gray-700: #334155;
        --gray-800: #1e293b;
    }

    body {
        background: var(--gray-50);
    }

    .form-section {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid var(--gray-200);
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }

    .form-section h6 {
        font-weight: 600;
        margin-bottom: 20px;
        color: var(--gray-800);
        border-left: 3px solid var(--danger);
        padding-left: 12px;
        font-size: 14px;
    }

    /* POS Cards */
    .pos-card {
        border: 1.5px solid var(--gray-200);
        border-radius: 16px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 100%;
        background: #ffffff;
    }

    .pos-card:hover {
        border-color: var(--danger);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px -12px rgba(0,0,0,0.15);
    }

    .pos-card h5 {
        font-size: 16px;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 8px;
    }

    .pos-card small {
        font-size: 11px;
        color: var(--gray-500);
    }

    .pos-selected {
        border: 2px solid var(--danger);
        background: #fef2f2;
    }

    /* Product Boxes */
    .product-box {
        border: 1.5px solid var(--gray-200);
        border-radius: 16px;
        padding: 16px 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        height: 100%;
        background: #ffffff;
    }

    .product-box:hover {
        border-color: var(--danger);
        background: #fef2f2;
        transform: translateY(-2px);
    }

    .product-box i {
        font-size: 32px;
        color: var(--primary);
        opacity: 0.7;
    }

    .product-box h6 {
        font-size: 13px;
        font-weight: 600;
        margin-top: 12px;
        margin-bottom: 6px;
        color: var(--gray-800);
    }

    /* Cart Table */
    .cart-table {
        width: 100%;
        border-collapse: collapse;
    }

    .cart-table thead th {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-500);
        background: var(--gray-50);
        padding: 12px 12px;
        border-bottom: 1px solid var(--gray-200);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .cart-table tbody td {
        font-size: 13px;
        color: var(--gray-700);
        padding: 12px;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
    }

    /* Quantity Controls */
    .qty-btn {
        border: 1.5px solid var(--gray-200);
        background: #ffffff;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.2s;
        cursor: pointer;
    }

    .qty-btn:hover {
        border-color: var(--danger);
        color: var(--danger);
    }

    .item-qty {
        width: 60px;
        text-align: center;
        border: 1.5px solid var(--gray-200);
        border-radius: 10px;
        padding: 6px 8px;
        font-size: 13px;
        font-weight: 500;
    }

    .item-qty:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30,58,95,0.08);
    }

    /* Form Controls */
    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-600);
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        border: 1.5px solid var(--gray-200);
        border-radius: 12px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--gray-700);
        background: #ffffff;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(30,58,95,0.08);
    }

    select.form-control {
        cursor: pointer;
    }

    /* Buttons */
    .btn-danger {
        background: var(--danger);
        border: none;
        border-radius: 12px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-danger:hover {
        background: var(--danger-dark);
        transform: translateY(-1px);
    }

    /* Category Headers */
    .category-header h5 {
        font-size: 14px;
        font-weight: 700;
        color: var(--danger);
        border-bottom: 2px solid var(--gray-200);
        padding-bottom: 10px;
        margin-bottom: 16px;
    }

    /* Preloader */
    .preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.3);
        z-index: 999999;
        display: none;
        justify-content: center;
        align-items: center;
        backdrop-filter: blur(4px);
    }

    .loader {
        width: 60px;
        height: 60px;
        border: 4px solid rgba(255,255,255,0.3);
        border-top: 4px solid #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Breadcrumb */
    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-bottom: 1rem;
    }

    .breadcrumb-item a {
        text-decoration: none;
        color: var(--gray-500);
        font-size: 12px;
    }

    .breadcrumb-item.active {
        color: var(--primary);
        font-weight: 600;
    }

    /* Remove Row Button */
    .btn-remove-row {
        background: #fef2f2;
        border: none;
        border-radius: 8px;
        width: 28px;
        height: 28px;
        font-size: 14px;
        font-weight: 700;
        transition: all 0.2s;
    }

    .btn-remove-row:hover {
        background: var(--danger);
        color: #ffffff;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-section {
            padding: 16px;
        }
        
        .product-box i {
            font-size: 24px;
        }
        
        .product-box h6 {
            font-size: 11px;
        }
        
        .pos-card h5 {
            font-size: 14px;
        }
        
        .btn-danger {
            width: 100%;
        }
    }
</style>

<div class="me-pos-msg"></div>
<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>"/>
<div class="preloader">
    <div class="loader"></div>
</div>
<div class="row mb-2">
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

<form class="pos-reg-form" id="posRegForm">
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
                    <div class="col-md-6 mb-3">
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
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Membership Plan</label>
                        <select class="form-control" id="membership_plan">
                            <option value="1000" data-plan="1 Month">1 Month</option>
                            <option value="3000" data-plan="3 Months">3 Months</option>
                            <option value="6000" data-plan="6 Months">6 Months</option>
                            <option value="12000" data-plan="12 Months">12 Months</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="membership_start_date" id="membership_start_date" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" name="membership_end_date" id="membership_end_date" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Status</label>
                        <select name="membership_status" id="membership_status" class="form-control">
                            <option value="Active">Active</option>
                            <option value="Pending">Pending</option>
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
                        <input type="text" class="form-control" id="walkin_name" name="walkin_name">
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

                <?php
                $categories = ['Merchandise', 'Accessories', 'Beverages', 'Food', 'Supplements'];

                foreach($categories as $cat):
                ?>

                <!-- CATEGORY TITLE -->
                <div class="mb-1 mt-4">
                    <h5 class="fw-bold text-danger border-bottom pb-2">
                        <?=$cat;?>
                    </h5>
                </div>

                <div class="row">

                    <?php
                    $products_query = $this->db->query("
                        SELECT 
                            product_name,
                            selling_price,
                            stock_qty,
                            category
                        FROM tbl_products
                        WHERE status = 'ACTIVE'
                        AND category = '$cat'
                        ORDER BY product_name ASC
                    ");

                    foreach($products_query->getResultArray() as $prod):
                    ?>

                    <div class="col-md-3 mb-1">

                        <div class="product-box"
                            onclick="addItem(
                                '<?=$prod['product_name'];?>',
                                '<?=$prod['category'];?>',
                                <?=$prod['selling_price'];?>
                            )">

                            <?php
                            if($prod['category'] == 'Supplements'){
                                echo '<i class="ti ti-barbell fs-8"></i>';
                            }elseif($prod['category'] == 'Beverages'){
                                echo '<i class="ti ti-bottle fs-8"></i>';
                            }elseif($prod['category'] == 'Food'){
                                echo '<i class="ti ti-egg fs-8"></i>';
                            }elseif($prod['category'] == 'Accessories'){
                                echo '<i class="ti ti-device-watch fs-8"></i>';
                            }else{
                                echo '<i class="ti ti-package fs-8"></i>';
                            }
                            ?>

                            <h6 class="mt-2">
                                <?=$prod['product_name'];?>
                            </h6>

                            <div class="fw-semibold text-danger">
                                ₱<?=number_format($prod['selling_price'],2);?>
                            </div>

                            <small class="text-muted d-block mt-1">
                                Stock: <?=$prod['stock_qty'];?>
                            </small>

                        </div>

                    </div>

                    <?php endforeach; ?>

                </div>

                <?php endforeach; ?>

            </div>
        </div>

        <!-- RIGHT SIDE -->
        <div class="col-md-5">
            <div class="form-section">
                <h6><i class="ti ti-receipt me-2"></i> POS Cart</h6>
                <div class="table-responsive">
                    <table class="table cart-table">
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