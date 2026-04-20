<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

echo view('templates/myheader.php');
?>

<style>
    /* Professional Report Styles */
    .report-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05), 0 1px 2px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    
    .report-header {
        background: #fff;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #eef2f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .report-header h5 {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .report-header h5 i {
        color: #4361ee;
        font-size: 1.1rem;
    }
    
    .report-body {
        padding: 1.5rem;
    }
    
    /* Report Type Selector */
    .report-type-selector {
        background: #f8fafc;
        border-radius: 10px;
        padding: 0.25rem;
        display: inline-flex;
        gap: 0.25rem;
    }
    
    .report-type-btn {
        padding: 0.5rem 1.25rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 8px;
        border: none;
        background: transparent;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .report-type-btn.active {
        background: #4361ee;
        color: #fff;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    
    .report-type-btn:hover:not(.active) {
        background: #eef2ff;
        color: #4361ee;
    }
    
    /* Date Range Controls */
    .date-controls {
        background: #fff;
        border: 1px solid #e9ecef;
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
    }
    
    .date-controls .form-control-sm:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67,97,238,0.1);
    }
    
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
    }
    
    .btn-generate:hover {
        background: #2e4ad0;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(67,97,238,0.2);
    }
    
    /* PDF Viewer */
    .pdf-viewer {
        width: 100%;
        height: 550px;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        position: relative;
        background: #f8fafc;
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
    }
    
    .pdf-placeholder small {
        font-size: 0.7rem;
        color: #cbd5e1;
    }
    
    /* Breadcrumb */
    .breadcrumb {
        font-size: 0.75rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .report-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .report-type-selector {
            width: 100%;
            justify-content: center;
        }
        
        .report-type-btn {
            flex: 1;
            text-align: center;
        }
        
        .date-controls .row {
            flex-direction: column;
            gap: 0.5rem;
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
    <!-- Page Header -->
    <div class="row mb-3">
        <div class="col-12">
            <h4 class="fw-semibold mb-1" style="color: #1e293b;">Accounting Reports</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a class="text-muted text-decoration-none" href="<?=site_url();?>">
                            <i class="ti ti-home fs-5"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Accounting</li>
                    <li class="breadcrumb-item active fw-semibold">Reports</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <!-- Main Report Card -->
    <div class="report-card">
        <div class="report-header">
            <h5>
                <i class="ti ti-file-report"></i>
                Financial Reports
            </h5>
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
        </div>
        
        <div class="report-body">
            <!-- Date Controls - Dynamic based on report type -->
            <div class="date-controls" id="dateControls">
                <!-- Content will be populated by JavaScript -->
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
            url: '<?= base_url('reports/cash-receipts')?>',
            type: 'range',
            icon: 'ti ti-credit-card'
        },
        'cash-disbursement': {
            title: 'Cash Disbursement Journal',
            url: '<?= base_url('reports/cash-disbursement')?>',
            type: 'range',
            icon: 'ti ti-wallet'
        },
        'balance-sheet': {
            title: 'Balance Sheet',
            url: '<?= base_url('reports/balance-sheet')?>',
            type: 'single',
            icon: 'ti ti-report-money'
        },
        'income-statement': {
            title: 'Income Statement',
            url: '<?= base_url('reports/income-statement')?>',
            type: 'range',
            icon: 'ti ti-chart-line'
        },
        'cash-flow': {
            title: 'Cash Flow Statement',
            url: '<?= base_url('reports/cash-flow')?>',
            type: 'range',
            icon: 'ti ti-arrows-transfer-up-down'
        }
    };
    
    // Render date controls based on report type
    function renderDateControls(reportKey) {
        const report = reports[reportKey];
        const isRange = report.type === 'range';
        
        let html = `
            <div class="row g-3 align-items-end">
                <div class="col-sm-4">
                    <label class="form-label">${isRange ? 'From Date' : 'Report Date'}</label>
                    <input type="date" id="dateFrom" class="form-control form-control-sm" placeholder="From Date">
                </div>
        `;
        
        if (isRange) {
            html += `
                <div class="col-sm-4">
                    <label class="form-label">To Date</label>
                    <input type="date" id="dateTo" class="form-control form-control-sm" placeholder="To Date">
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
            
            fullUrl += '?date_from=' + fromDate + '&date_to=' + toDate;
        } else {
            const date = $('#dateFrom').val();
            
            if (!date) {
                alert('Please select a date');
                return;
            }
            
            fullUrl += '?date=' + date;
        }
        
        // Load PDF
        $('#reportFrame').attr('src', fullUrl).show();
        $('#reportPlaceholder').hide();
        
        // Update header title
        $('.report-header h5').html(`
            <i class="${report.icon}"></i>
            ${report.title}
        `);
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
        
        // Update header title
        const report = reports[currentReport];
        $('.report-header h5').html(`
            <i class="${report.icon}"></i>
            ${report.title}
        `);
    });
    
    // Initialize with default report
    renderDateControls('cash-receipts');
});
</script>

<?php echo view('templates/myfooter.php'); ?>