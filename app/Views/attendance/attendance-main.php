<!-- ===================================================== -->
<!-- ATTENDANCE MODULE VIEW -->
<!-- FILE: app/Views/attendance/attendance-main.php -->
<!-- ===================================================== -->

<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

echo view('templates/myheader.php');
?>

<div class="attendance-msg"></div>

<input type="hidden" id="__siteurl" data-mesiteurl="<?=site_url();?>" />

<div class="row mt-4">
    <div class="col-12">
        <h4 class="fw-bold">Attendance Management</h4>
    </div>
</div>

<!-- ===================================================== -->
<!-- MONTHLY SUBSCRIPTION -->
<!-- ===================================================== -->

<div class="row">
    <div class="col-sm-6">
        <div class="card mt-3">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Monthly Subscription Members</h6>

                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#walkinModal" >
                    Add Walk-In
                </button>
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th width="10%">Action</th>
                                <th>Member No</th>
                                <th>Member Name</th>
                                <th>Membership</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if(!empty($membersdata)): ?>
                                <?php foreach($membersdata as $row): ?>

                                    <tr>

                                        <td class="text-center">

                                            <button 
                                                class="btn btn-success btn-sm btn-checkin-member"
                                                data-member_id="<?=$row['member_id'];?>"
                                                data-member_name="<?=$row['first_name'];?> <?=$row['last_name'];?>"
                                            >
                                                Check-In
                                            </button>

                                        </td>

                                        <td><?=$row['member_no'];?></td>

                                        <td>
                                            <?=$row['first_name'];?> <?=$row['last_name'];?>
                                        </td>

                                        <td><?=$row['membership_plan'];?></td>

                                        <td>

                                            <?php if($row['membership_status'] == 'Active'): ?>
                                                <span class="badge bg-success">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Inactive</span>
                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="card mt-4">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Walk-In Customers</h6>

                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#walkinModal">
                    Add Walk-In
                </button>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Payment Method</th>
                                <th>Payment</th>
                                <th>Check-In Date</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if(!empty($walkindata)): ?>
                                <?php foreach($walkindata as $row): ?>

                                    <tr>

                                        <td><?=$row['customer_name'];?></td>
                                        <td><?=$row['email'];?></td>
                                        <td><?=$row['payment_method'];?></td>
                                        <td><?=number_format($row['payment_amount'],2);?></td>
                                        <td><?=$row['checkin_datetime'];?></td>

                                    </tr>

                                <?php endforeach; ?>
                            <?php endif; ?>

                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="card mt-4 mb-5">

    <div class="card-header bg-primary text-white">
        <h6 class="mb-0">Attendance Logs</h6>
    </div>

    <div class="card-body">

        <div class="table-responsive">

            <table class="table table-bordered table-striped align-middle">

                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Payment</th>
                        <th>Payment Method</th>
                        <th>Date & Time</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if(!empty($checkinhistory)): ?>
                        <?php foreach($checkinhistory as $row): ?>

                            <tr>

                                <td>
                                    <?php if($row['attendance_type'] == 'WALKIN'): ?>
                                        <span class="badge bg-warning text-dark">Walk-In</span>
                                    <?php else: ?>
                                        <span class="badge bg-success">Member</span>
                                    <?php endif; ?>
                                </td>

                                <td><?=$row['customer_name'];?></td>

                                <td>
                                    <?=number_format((float)$row['payment_amount'],2);?>
                                </td>

                                <td><?=$row['payment_method'];?></td>

                                <td><?=$row['checkin_datetime'];?></td>

                            </tr>

                        <?php endforeach; ?>
                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<!-- ===================================================== -->
<!-- WALKIN MODAL -->
<!-- ===================================================== -->

<div class="modal fade" id="walkinModal">

    <div class="modal-dialog">
        <div class="modal-content">

            <form class="walkin-form">

                <div class="modal-header">
                    <h5 class="modal-title">Add Walk-In</h5>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Payment Method</label>

                        <select name="payment_method" class="form-control">

                            <option value="Cash">Cash</option>
                            <option value="GCash">GCash</option>
                            <option value="Card">Card</option>

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Payment Amount</label>
                        <input type="number" step="0.01" name="payment_amount" class="form-control" required>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="submit" class="btn btn-success">
                        Save Walk-In
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

var mesiteurl = $('#__siteurl').attr('data-mesiteurl');


// =====================================================
// MEMBER CHECKIN
// =====================================================

$(document).on('click','.btn-checkin-member',function(){

    var member_id = $(this).data('member_id');
    var member_name = $(this).data('member_name');

    $.ajax({
        type: "POST",
        url: mesiteurl + 'attendance',
        data: {
            member_id : member_id,
            attendance_type : 'MEMBER',
            meaction : 'SAVE-CHECKIN'
        },

        success: function(data){

            toastr.success(member_name + ' checked in successfully');

            setTimeout(function(){
                location.reload();
            },1000);
        }
    });

});


// =====================================================
// WALKIN SAVE
// =====================================================

$(document).on('submit','.walkin-form',function(e){

    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({

        type : "POST",
        url : mesiteurl + 'attendance',
        data : formData + '&attendance_type=WALKIN&meaction=SAVE-WALKIN',

        success:function(data){

            toastr.success('Walk-In saved');

            setTimeout(function(){
                location.reload();
            },1000);

        }

    });

});

</script>

<?php
echo view('templates/myfooter.php');
?>