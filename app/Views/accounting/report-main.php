<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

echo view('templates/myheader.php');
?>

<style>
    /* Professional Report Styles - Matching Other Modules */
    .report-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.03);
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .report-header {
        background: #fff;
        border-bottom: 1px solid #eef2f6;
        padding: 1rem 1.5rem;
    }
    
    .report-header h6 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .report-header h6 i {
        color: #4361ee;
        font-size: 1rem;
    }
    
    .report-body {
        padding: 1.5rem;
    }
    
    /* Report Type Selector - Like Filter Buttons */
    .report-type-selector {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }
    
    .report-type-btn {
        padding: 0.35rem 1rem;
        font-size: 0.75rem;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
        font-weight: 500;
    }
    
    .report-type-btn.active {
        background: #4361ee;
        border-color: #4361ee;
        color: #fff;
    }
    
    .report-type-btn:hover:not(.active) {
        background: #f8fafc;
        border-color: #cbd5e1;
    }
    
    /* Date Controls - Matching Form Styles */
    .date-controls {
        background: #fff;
        border: 1px solid #eef2f6;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .date-controls .form-label {
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.25rem;
        display: block;
    }
    
    .date-controls .form-control-sm {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }
    
    .date-controls .form-control-sm:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
        outline: none;
    }
    
    /* Generate Button - Matching Module Buttons */
    .btn-generate {
        background: #4361ee;
        border: none;
        color: #fff;
        padding: 0.5rem 1.25rem;
        font-size: 0.8125rem;
        font-weight: 500;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    
    .btn-generate:hover {
        background: #2e4ad0;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(67,97,238,0.15);
    }
    
    .btn-generate i {
        font-size: 0.875rem;
    }
    
    /* PDF Viewer */
    .pdf-viewer {
        width: 100%;
        height: 550px;
        border: 1px solid #eef2f6;
        border-radius: 10px;
        position: relative;
        background: #fafbfc;
        overflow: hidden;
    }
    
    .pdf-frame {
        width: 100%;
        height: 100%;
        border: none;
        display: none;
    }
    
    .pdf-placeholder {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #fafbfc;
        color: #94a3b8;
        text-align: center;
    }
    
    .pdf-placeholder i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }
    
    .pdf-placeholder p {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
        color: #64748b;
    }
    
    .pdf-placeholder small {
        font-size: 0.7rem;
        color: #94a3b8;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .report-body {
            padding: 1rem;
        }
        
        .report-type-selector {
            justify-content: center;
        }
        
        .report-type-btn {
            flex: 1;
            text-align: center;
        }
        
        .date-controls .row {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .date-controls .col-sm-4 {
            width: 100%;
        }
        
        .btn-generate {
            width: 100%;
            justify-content: center;
        }
        
        .pdf-viewer {
            height: 400px;
        }
    }
</style>

<div class="container-fluid">
    <!-- Page Header - Same as Other Modules -->
    <div class="row mb-2 mt-0">
        <h4 class="fw-semibold mb-8">Accounting Reports</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a class="text-muted text-decoration-none" href="<?=site_url();?>"><i class="ti ti-home fs-5"></i></a>
                </li>
                <li class="breadcrumb-item" aria-current="page">Accounting</li>
                <li class="breadcrumb-item" aria-current="page"><span class="form-label fw-bold">Reports</span></li>
            </ol>
        </nav>
    </div>
    
    <!-- Main Report Card - Same Card Style as Other Modules -->
    <div class="card">
        <div class="card-header p-1">
            <div class="row">
                <div class="col-sm-6 d-flex align-items-center text-start">
                    <h6 class="mb-0 lh-base px-3 fw-semibold d-flex align-items-center">
                        <i class="ti ti-file-report fs-5 me-1"></i>
                        <span class="pt-1">Financial Reports</span>
                    </h6>
                </div>
                <div class="col-sm-6 text-end pe-3">
                    <!-- Optional: Additional actions can go here -->
                </div>
            </div>
        </div>
        
        <div class="card-body p-0 px-4 py-3 my-1">
            <!-- Report Type Selector -->
            <div class="report-type-selector">
                <button class="report-type-btn active" data-report="cash-receipts">
                    <i class="ti ti-credit-card"></i> Cash Receipts
                </button>
                <button class="report-type-btn" data-report="cash-disbursement">
                    <i class="ti ti-wallet"></i> Cash Disbursement
                </button>
                <button class="report-type-btn" data-report="balance-sheet">
                    <i class="ti ti-report-money"></i> Balance Sheet
                </button>
                <button class="report-type-btn" data-report="income-statement">
                    <i class="ti ti-chart-line"></i> Income Statement
                </button>
                <button class="report-type-btn" data-report="cash-flow">
                    <i class="ti ti-arrows-transfer-up-down"></i> Cash Flow
                </button>
            </div>
            
            <!-- Date Controls - Dynamic -->
            <div id="dateControls">
                <!-- Populated by JavaScript -->
            </div>
            
            <!-- PDF Viewer -->
            <div class="pdf-viewer">
                <iframe id="reportFrame" class="pdf-frame"></iframe>
                <div id="reportPlaceholder" class="pdf-placeholder">
                    <i class="ti ti-file-report"></i>
                    <p>Select a report type and date range</p>
                    <small>Click "Generate Report" to view</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    let currentReport = 'cash-receipts';
    
    // Report configurations
    const reports = {
        'cash-receipts': {
            title: 'Cash Receipts Journal',
            url: '<?= base_url('myaccountingreport?meaction=cash-receipts')?>',
            type: 'range',
            icon: 'ti ti-credit-card'
        },
        'cash-disbursement': {
            title: 'Cash Disbursement Journal',
            url: '<?= base_url('myaccountingreport?meaction=cash-disbursement')?>',
            type: 'range',
            icon: 'ti ti-wallet'
        },
        'balance-sheet': {
            title: 'Balance Sheet',
            url: '<?= base_url('myaccountingreport?meaction=balance-sheet')?>',
            type: 'single',
            icon: 'ti ti-report-money'
        },
        'income-statement': {
            title: 'Income Statement',
            url: '<?= base_url('myaccountingreport?meaction=income-statement')?>',
            type: 'range',
            icon: 'ti ti-chart-line'
        },
        'cash-flow': {
            title: 'Cash Flow Statement',
            url: '<?= base_url('myaccountingreport?meaction=cash-flow')?>',
            type: 'range',
            icon: 'ti ti-arrows-transfer-up-down'
        }
    };
    
    // Render date controls based on report type
    function renderDateControls(reportKey) {
        const report = reports[reportKey];
        const isRange = report.type === 'range';
        
        let html = `
            <div class="date-controls">
                <div class="row g-3 align-items-end">
                    <div class="col-sm-4">
                        <label class="form-label">${isRange ? 'From Date' : 'Report Date'}</label>
                        <input type="date" id="dateFrom" class="form-control form-control-sm">
                    </div>
        `;
        
        if (isRange) {
            html += `
                    <div class="col-sm-4">
                        <label class="form-label">To Date</label>
                        <input type="date" id="dateTo" class="form-control form-control-sm">
                    </div>
            `;
        }
        
        html += `
                    <div class="col-sm-4">
                        <button class="btn-generate" onclick="generateReport()">
                            <i class="ti ti-printer"></i>
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('#dateControls').html(html);
        
        // Set default dates
        const today = new Date().toISOString().split('T')[0];
        const firstDayOfMonth = new Date(new Date().getFullYear(), new Date().getMonth(), 1).toISOString().split('T')[0];
        
        $('#dateFrom').val(firstDayOfMonth);
        if (isRange) {
            $('#dateTo').val(today);
        }
    }
    
    // Generate and load report
    window.generateReport = function() {
        const report = reports[currentReport];
        const isRange = report.type === 'range';
        
        let fullUrl = report.url;
        
        if (isRange) {
            const fromDate = $('#dateFrom').val();
            const toDate = $('#dateTo').val();
            
            if (!fromDate || !toDate) {
                alert('Please select both from and to dates');
                return;
            }
            
            fullUrl += '&date_from=' + fromDate + '&date_to=' + toDate;
        } else {
            const date = $('#dateFrom').val();
            
            if (!date) {
                alert('Please select a date');
                return;
            }
            
            fullUrl += '&date=' + date;
        }
        
        // Load PDF
        $('#reportFrame').attr('src', fullUrl).show();
        $('#reportPlaceholder').hide();
    };
    
    // Handle report type selection
    $('.report-type-btn').click(function() {
        $('.report-type-btn').removeClass('active');
        $(this).addClass('active');
        
        currentReport = $(this).data('report');
        renderDateControls(currentReport);
        
        // Reset PDF viewer
        $('#reportFrame').hide().attr('src', '');
        $('#reportPlaceholder').show();
    });
    
    // Initialize with default report
    renderDateControls('cash-receipts');
});
</script>

<?php echo view('templates/myfooter.php'); ?>