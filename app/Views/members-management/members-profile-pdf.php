<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

$member_id = $this->request->getPostGet('member_id');

require APPPATH . 'ThirdParty/fpdf/fpdf.php';

$currentDate = date("Y-m-d");
$formattedDate = date("F j, Y", strtotime($currentDate));

// ==========================
// FETCH DATA
// ==========================
$query = $this->db->query("
 SELECT
    `member_id`,
    `member_no`,
    `first_name`,
    `last_name`,
    `middle_name`,
    `date_of_birth`,
    `place_of_birth`,
    `age`,
    `civil_status`,
    `gender`,
    `tin`,
    `gsis_number`,
    `permanent_street`,
    `permanent_barangay`,
    `permanent_city`,
    `permanent_province`,
    `permanent_zip`,
    `present_street`,
    `present_barangay`,
    `present_city`,
    `present_province`,
    `present_zip`,
    `home_phone`,
    `office_phone`,
    `department_agency`,
    `position`,
    `salary_grade`,
    `beneficiary1_name`,
    `beneficiary1_address`,
    `beneficiary1_contact`,
    `beneficiary1_relationship`,
    `beneficiary2_name`,
    `beneficiary2_address`,
    `beneficiary2_contact`,
    `beneficiary2_relationship`,
    `address`,
    `contact_number`,
    `email`,
    `username`,
    `password`,
    `role`,
    `hash_password`
FROM tbl_members 
WHERE member_id = '$member_id'
");
$data = $query->getRowArray();

if(!$data){
    echo "Member not found!";
    exit;
}

$dob = !empty($data['date_of_birth']) ? date('F d, Y', strtotime($data['date_of_birth'])) : '';

// ==========================
// PDF CLASS
// ==========================
class PDF extends FPDF {

    public $showHeader = true;

    function Header(){
        if (!$this->showHeader) return;

        $this->SetFont('Arial','B',10);

        $x = ($this->GetPageWidth() - 120) / 2;

        $this->SetX($x);
        $this->Cell(120,5,'SCIENCE SAVINGS AND LOAN ASSOCIATION, INC. (SSLAI)','TRL',1,'C');

        $this->SetX($x);
        $this->Cell(120,5,'DOST Compd., Bicutan, Taguig City','RLB',1,'C');

        $this->Ln(2);

        $this->SetFont('Arial','B',12);
        $this->Cell(0,6,'SSLAI Membership Profile Update Form',0,1,'C');

        $this->Ln(3);
    }

    function Footer(){
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Page '.$this->PageNo().' of {nb}',0,0,'C');
    }
    
    // Helper function to draw checkbox
    function CheckBox($checked, $x, $y, $size = 4) {
        // Draw the box border
        $this->Rect($x, $y, $size, $size);
        
        if ($checked) {
            // Save current font
            $currentFont = $this->FontFamily;
            $currentStyle = $this->FontStyle;
            $currentSize = $this->FontSizePt;
            
            // Use ZapfDingbats for checkmark
            $this->SetFont('ZapfDingbats', '', 8);
            
            // Calculate position to center the checkmark
            // The checkmark symbol '4' needs to be centered in the box
            $this->SetXY($x + ($size / 2) - 1.5, $y + ($size / 2) - 2);
            $this->Cell(3, 3, '4', 0, 0, 'C');
            
            // Restore original font
            $this->SetFont($currentFont, $currentStyle, $currentSize);
        }
}
}

// ==========================
// INIT PDF
// ==========================
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15,10,15);
$pdf->SetTitle('SSLAI Membership Profile Update Form');

// Logo
$imagePath = FCPATH . 'assets/images/logos/sslai.png';
if(file_exists($imagePath)) {
    $pdf->Image($imagePath, 10, 5, 20);
}

// ==========================
// I. MEMBER INFO
// ==========================
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'I. Member Information',0,1);

$pdf->SetFont('Arial','',10);

// 1
$pdf->Cell(45,5,'1. Member ID No.',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(130,5,$data['member_no'],'B',1);

$pdf->Ln(1);

// 2 NAME
$pdf->Cell(45,5,'2. Name',0,0);
$pdf->Cell(5,5,':',0,0);

$pdf->Cell(44,5,$data['last_name'],'B',0);
$pdf->Cell(44,5,$data['first_name'],'B',0);
$pdf->Cell(42,5,$data['middle_name'],'B',1);

$pdf->Cell(47,5,'',0,0);
$pdf->Cell(44,5,'Last Name',0,0,'C');
$pdf->Cell(44,5,'First Name',0,0,'C');
$pdf->Cell(42,5,'Middle Name',0,1,'C');

$pdf->Ln(1);

// 3
$pdf->Cell(45,5,'3. Date of Birth',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(130,5,$dob,'B',1);

// 4
$pdf->Cell(45,5,'4. Place of Birth',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(130,5,$data['place_of_birth'],'B',1);

// 5
$pdf->Cell(45,5,'5. Age',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(130,5,$data['age'],'B',1);

$pdf->Ln(2);

// 6 CIVIL STATUS - with checkboxes
$pdf->Cell(45,5,'6. Civil Status',0,0);
$pdf->Cell(5,5,':',0,0);

$startX = $pdf->GetX();
$startY = $pdf->GetY();

// Single checkbox
$pdf->CheckBox($data['civil_status'] == 'Single', $startX, $startY);
$pdf->SetXY($startX + 6, $startY);
$pdf->Cell(18,5,'Single',0,0);

// Married checkbox
$pdf->CheckBox($data['civil_status'] == 'Married', $startX + 30, $startY);
$pdf->SetXY($startX + 36, $startY);
$pdf->Cell(20,5,'Married',0,0);

// Widowed checkbox
$pdf->CheckBox($data['civil_status'] == 'Widowed', $startX + 62, $startY);
$pdf->SetXY($startX + 68, $startY);
$pdf->Cell(22,5,'Widowed',0,0);

// Divorced checkbox
$pdf->CheckBox($data['civil_status'] == 'Divorced', $startX + 96, $startY);
$pdf->SetXY($startX + 102, $startY);
$pdf->Cell(21,5,'Divorced',0,1);

$pdf->Ln(3);

// 7 GENDER - with checkboxes
$pdf->Cell(45,5,'7. Gender',0,0);
$pdf->Cell(5,5,':',0,0);

$startX = $pdf->GetX();
$startY = $pdf->GetY();

// Male checkbox
$pdf->CheckBox($data['gender'] == 'Male', $startX, $startY);
$pdf->SetXY($startX + 6, $startY);
$pdf->Cell(20,5,'Male',0,0);

// Female checkbox
$pdf->CheckBox($data['gender'] == 'Female', $startX + 32, $startY);
$pdf->SetXY($startX + 38, $startY);
$pdf->Cell(22,5,'Female',0,0);

// Other checkbox
$pdf->CheckBox($data['gender'] == 'Other', $startX + 66, $startY);
$pdf->SetXY($startX + 72, $startY);
$pdf->Cell(20,5,'Other',0,1);

$pdf->Ln(3);

// 8
$pdf->Cell(45,5,'8. TIN',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(130,5,$data['tin'],'B',1);

// 9
$pdf->Cell(45,5,'9. GSIS Number',0,0);
$pdf->Cell(5,5,':',0,0);
$pdf->Cell(130,5,$data['gsis_number'],'B',1);

$pdf->Ln(4);

// ==========================
// II. CONTACT INFO
// ==========================
$pdf->SetX(10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'II. Contact Information',0,1);
$pdf->SetFont('Arial','',10);

// 1 Permanent Address
$pdf->Cell(45,4,'1. Permanent Address',0,0);
$pdf->Cell(4,4,'',0,0,'R');
$pdf->Cell(5,4,':',0,0,'C');

$pdf->Cell(44,4,$data['permanent_street'],'B',0);
$pdf->Cell(44,4,$data['permanent_barangay'],'B',0);
$pdf->Cell(38,4,$data['permanent_city'],'B',1);

$pdf->Cell(47,4,'',0,0);
$pdf->Cell(44,4,'Street',0,0,'C');
$pdf->Cell(44,4,'Barangay',0,0,'C');
$pdf->Cell(38,4,'City/Municipality',0,1,'C');

$pdf->Ln(1);

$pdf->Cell(45,4,'',0,0);
$pdf->Cell(4,4,'',0,0,'R');
$pdf->Cell(5,4,'',0,0,'C');

$pdf->Cell(44,4,$data['permanent_province'],'B',0);
$pdf->Cell(82,4,$data['permanent_zip'],'B',1);

$pdf->Cell(47,4,'',0,0);
$pdf->Cell(44,4,'Province',0,0,'C');
$pdf->Cell(82,4,'Zip Code',0,1,'C');

$pdf->Ln(2);

// 2 Present Address
$pdf->Cell(45,4,'2. Present Address',0,0);
$pdf->Cell(4,4,'',0,0,'R');
$pdf->Cell(5,4,':',0,0,'C');

$pdf->Cell(44,4,$data['present_street'],'B',0);
$pdf->Cell(44,4,$data['present_barangay'],'B',0);
$pdf->Cell(38,4,$data['present_city'],'B',1);

$pdf->Cell(47,4,'',0,0);
$pdf->Cell(44,4,'Street',0,0,'C');
$pdf->Cell(44,4,'Barangay',0,0,'C');
$pdf->Cell(38,4,'City/Municipality',0,1,'C');

$pdf->Ln(1);

$pdf->Cell(45,4,'',0,0);
$pdf->Cell(4,4,'',0,0,'R');
$pdf->Cell(5,4,'',0,0,'C');

$pdf->Cell(44,4,$data['present_province'],'B',0);
$pdf->Cell(82,4,$data['present_zip'],'B',1);

$pdf->Cell(47,4,'',0,0);
$pdf->Cell(44,4,'Province',0,0,'C');
$pdf->Cell(82,4,'Zip Code',0,1,'C');

$pdf->Ln(2);

// Contact numbers
$pdf->Cell(45,5,'3. Mobile Number',0,0);
$pdf->Cell(4,5,'',0,0,'R');
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(126,5,$data['contact_number'],'B',1);

$pdf->Cell(45,5,'4. Home Phone Number',0,0);
$pdf->Cell(4,5,'',0,0,'R');
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(126,5,$data['home_phone'],'B',1);

$pdf->Cell(45,5,'5. Office Phone Number',0,0);
$pdf->Cell(4,5,'',0,0,'R');
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(126,5,$data['office_phone'],'B',1);

$pdf->Cell(45,5,'6. Email Address',0,0);
$pdf->Cell(4,5,'',0,0,'R');
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(126,5,$data['email'],'B',1);

$pdf->Ln(4);

// ==========================
// III. EMPLOYMENT
// ==========================
$pdf->SetX(10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'III. Employment Information',0,1);
$pdf->SetFont('Arial','',10);

// Department/Agency with checkboxes
$pdf->Cell(45,5,'1. Department/Agency',0,0);
$pdf->Cell(4,5,'',0,0,'R');
$pdf->Cell(5,5,':',0,0,'C');

$startX = $pdf->GetX();
$startY = $pdf->GetY();

// DOST-FNRI checkbox
$pdf->CheckBox($data['department_agency'] == 'DOST-FNRI', $startX, $startY);
$pdf->SetXY($startX + 6, $startY);
$pdf->Cell(25,5,'DOST-FNRI',0,0);

// DOST-ITDI checkbox
$pdf->CheckBox($data['department_agency'] == 'DOST-ITDI', $startX + 40, $startY);
$pdf->SetXY($startX + 46, $startY);
$pdf->Cell(25,5,'DOST-ITDI',0,1);

$pdf->Ln(1);

// Position
$pdf->Cell(45,5,'2. Position',0,0);
$pdf->Cell(4,5,'',0,0,'R');
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(126,5,$data['position'],'B',1);

// Salary Grade
$pdf->Cell(45,5,'3. Salary Grade',0,0);
$pdf->Cell(4,5,'',0,0,'R');
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(126,5,$data['salary_grade'],'B',1);

$pdf->Ln(4);

// ==========================
// IV. BENEFICIARIES
// ==========================
$pdf->SetX(10);
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'IV. Contact Person(s)/Beneficiaries',0,1);

$pdf->SetFont('Arial','',10);

// Beneficiary 1
$pdf->Cell(45,5,'1. Name of Beneficiary (1)',0,0);
$pdf->Cell(8,5,'',0,0,'R');
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(122,5,$data['beneficiary1_name'],'B',1);

$pdf->Cell(12,5,'',0,0,'R');
$pdf->Cell(41,5,'Address',0,0);
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(122,5,$data['beneficiary1_address'],'B',1);

$pdf->Cell(12,5,'',0,0,'R');
$pdf->Cell(41,5,'Contact Number',0,0);
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(122,5,$data['beneficiary1_contact'],'B',1);

$pdf->Cell(12,5,'',0,0,'R');
$pdf->Cell(41,5,'Relationship',0,0);
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(122,5,$data['beneficiary1_relationship'],'B',1);

$pdf->Ln(2);

// Beneficiary 2
$pdf->Cell(45,5,'2. Name of Beneficiary (2)',0,0);
$pdf->Cell(8,5,'',0,0,'R');
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(122,5,$data['beneficiary2_name'],'B',1);

$pdf->Cell(12,5,'',0,0,'R');
$pdf->Cell(41,5,'Address',0,0);
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(122,5,$data['beneficiary2_address'],'B',1);

$pdf->Cell(12,5,'',0,0,'R');
$pdf->Cell(41,5,'Contact Number',0,0);
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(122,5,$data['beneficiary2_contact'],'B',1);

$pdf->Cell(12,5,'',0,0,'R');
$pdf->Cell(41,5,'Relationship',0,0);
$pdf->Cell(5,5,':',0,0,'C');
$pdf->Cell(122,5,$data['beneficiary2_relationship'],'B',1);

$pdf->Ln(4);

// ==========================
// DECLARATION
// ==========================
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(0,4,
"Declaration: I hereby certify that the information provided above is true and correct to the best of my knowledge. I authorize SSLAI to use this information for the purpose of updating my records."
);
$pdf->SetFont('Arial','',10);
$pdf->Ln(6);

$pdf->Cell(60,4,'','B',1);
$pdf->Cell(60,4,'Signature of Member:',0,1);

$pdf->Ln(1);

$pdf->Cell(15,4,'Date:',0,0);
$pdf->Cell(45,4,$currentDate,'B',1);

// ==========================
// PAGE 2 - PRIVACY STATEMENT
// ==========================
$pdf->showHeader = false;
$pdf->AddPage();

$Y = 10;

$pdf->SetFont('Arial', 'B', 14);
$pdf->SetXY(0, $Y);
$pdf->Cell(210, 10, 'PRIVACY STATEMENT', 0, 1, 'C');

$Y = $pdf->GetY() + 8;
$pdf->SetFont('Arial', '', 12);

$bullet = chr(149);

$privacy_text = "In compliance with the Data Privacy Act of 2012 (Republic Act No. 10173), SSLAI commits to protecting the privacy of its members' personal information.\n\n";
$privacy_text .= "The information you provide will be used solely for the following purposes:\n\n";

$privacy_text .= "$bullet Member Account Maintenance: To update and maintain your membership records.\n";
$privacy_text .= "$bullet Transaction Processing: To process loans, savings deposits, and other financial transactions.\n";
$privacy_text .= "$bullet Communication: To send important notices, updates, and promotional materials.\n";
$privacy_text .= "$bullet Compliance: To comply with legal and regulatory requirements.\n\n";

$privacy_text .= "HOW WE PROTECT YOUR INFORMATION:\n\n";
$privacy_text .= "SSLAI implements security measures to safeguard your personal information. These measures include:\n\n";

$privacy_text .= "$bullet Data Encryption: Sensitive information is encrypted to prevent unauthorized access.\n";
$privacy_text .= "$bullet Access Controls: Access to your information is restricted to authorized personnel.\n\n";

$privacy_text .= "As a data subject, you have the following rights:\n\n";

$privacy_text .= "$bullet Right to Access: You have the right to access your personal information.\n";
$privacy_text .= "$bullet Right to Rectification: You have the right to request the rectification of inaccurate or incomplete personal information.\n";
$privacy_text .= "$bullet Right to Object: You have the right to object to the processing of your personal information.\n\n";

$privacy_text .= "If you have any questions or concerns regarding your privacy or data protection, please contact SSLAI Data Protection Officer at telephone no. 8838-5382.";

$pdf->SetXY(15, $Y);
$pdf->MultiCell(180, 6, $privacy_text, 0, 'L');

$Y = $pdf->GetY() + 15;

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(15, $Y);
$pdf->Cell(180, 7, 'CONFORME:', 0, 1, 'L');

$Y = $pdf->GetY() + 5;

$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(15, $Y);
$pdf->Cell(60, 7, 'Signature Over Printed Name', 0, 0, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(100, 7, '_________________________', 0, 1, 'L');

$Y = $pdf->GetY();

$pdf->SetFont('Arial', '', 12);
$pdf->SetXY(15, $Y);
$pdf->Cell(20, 7, 'Date:', 0, 0, 'L');

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(80, 7, $formattedDate, 'B', 1, 'L');

// ==========================
$pdf->Output('SSLAI_Membership_Profile_' . $data['member_no'] . '.pdf', 'I');
exit;
?>