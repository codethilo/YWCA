<?php
require('fpdf.php');

try {
    $pdo = new PDO('mysql:host=localhost;dbname=ywca', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_GET['receipt_no'])) {
        $receipt_no = $_GET['receipt_no'];

        // Fetch receipt details
        $stmt = $pdo->prepare("SELECT * FROM receipt_entries1 WHERE receipt_no = :receipt_no");
        $stmt->execute(['receipt_no' => $receipt_no]);
        $receipt = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($receipt) {
            // Membership year calculation
            $received_year = (int)date('Y', strtotime($receipt['received_date']));
            $membership_year = $received_year . '-' . ($received_year + 1);

            // Calculate service tax rate
            $service_tax_rate = $receipt['service_tax'] == '1' ? 18 : 0;

            // Calculate total amount
            $member_fee = $receipt['member_fee'] ?? 0;
            $joining_fee = $receipt['joining_fee'] ?? 0;
            $sub_total = $member_fee + $joining_fee;
            $service_tax_amount = ($sub_total * $service_tax_rate) / 100;
            $total_amount = $sub_total + $service_tax_amount;

            // Initialize PDF
            $pdf = new FPDF();
            $pdf->AddPage();
            $pdf->SetMargins(10, 8, 10); // Reduced margins
            $pdf->SetFont('Arial', 'B', 14); // Larger font for header

            // Header Section
            $pdf->SetFillColor(230, 230, 230);
            $pdf->Cell(0, 12, 'Y.W.C.A. OF MADRAS', 1, 1, 'C', true);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(0, 8, '1078 - 1087/2, Poonamallee High Road, Chennai - 600 084', 1, 1, 'C');
            $pdf->Cell(0, 8, 'Telephone: 2532 4251 / 2532 4261 | Email: membership@ywcamadras.org', 1, 1, 'C');
            $pdf->Ln(5);

            // Receipt Title
            $pdf->SetFont('Arial', 'B', 14); // Larger font for receipt title
            $pdf->Cell(0, 10, 'Receipt for ' . ($receipt['membership_type'] ?? 'Membership'), 1, 1, 'C', true);
            $pdf->Ln(3);

            // Receipt Details Section
            $pdf->SetFont('Arial', '', 11); // Slightly larger font
            $pdf->SetFillColor(245, 245, 245);
            $pdf->Cell(50, 7, 'Receipt No:', 1, 0, 'L', true);
            $pdf->Cell(0, 7, $receipt['receipt_no'], 1, 1);
            $pdf->Cell(50, 7, 'Date:', 1, 0, 'L', true);
            $pdf->Cell(0, 7, $receipt['received_date'], 1, 1);
            $pdf->Cell(50, 7, 'Membership Year:', 1, 0, 'L', true);
            $pdf->Cell(0, 7, $membership_year, 1, 1);

            // Personal Details Section
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 12); // Larger font for section titles
            $pdf->Cell(0, 7, 'Personal Details', 1, 1, 'L', true);
            $pdf->SetFont('Arial', '', 11); // Slightly larger font
            $pdf->Cell(50, 7, 'Member Name:', 1, 0);
            $pdf->Cell(0, 7, $receipt['member_name'], 1, 1);
            $pdf->Cell(50, 7, 'Father/Husband Name:', 1, 0);
            $pdf->Cell(0, 7, $receipt['father_husband_name'], 1, 1);
            $pdf->Cell(50, 7, 'Address:', 1, 0);
            $pdf->MultiCell(0, 7, $receipt['address'], 1); // Wraps text
            $pdf->Cell(50, 7, 'DOB:', 1);
            $pdf->Cell(0, 7, $receipt['dob'], 1, 1);
            $pdf->Cell(50, 7, 'Date Of Joining:', 1);
            $pdf->Cell(0, 7, $receipt['date_of_joining'], 1, 1);
            $pdf->Cell(50, 7, 'Email:', 1, 0);
            $pdf->Cell(0, 7, $receipt['email'], 1, 1);
            $pdf->Cell(50, 7, 'Mobile No:', 1, 0);
            $pdf->Cell(0, 7, $receipt['mobile_no'], 1, 1);

            // Membership Fee Details Section
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 12); // Larger font for section titles
            $pdf->Cell(0, 7, 'Membership Fee Details', 1, 1, 'L', true);
            $pdf->SetFont('Arial', '', 11); // Slightly larger font
            $pdf->Cell(50, 7, 'Member Fee:', 1, 0);
            $pdf->Cell(0, 7, number_format($member_fee, 2) . ' INR', 1, 1);
            $pdf->Cell(50, 7, 'Joining Fee:', 1, 0);
            $pdf->Cell(0, 7, number_format($joining_fee, 2) . ' INR', 1, 1);
            $pdf->Cell(50, 7, 'Service Tax:', 1, 0);
            $pdf->Cell(0, 7, $service_tax_rate . '%', 1, 1);
            $pdf->Cell(50, 7, 'Service Tax Amount:', 1, 0);
            $pdf->Cell(0, 7, number_format($service_tax_amount, 2) . ' INR', 1, 1);
            $pdf->Cell(50, 7, 'Total Amount:', 1, 0);
            $pdf->Cell(0, 7, number_format($total_amount, 2) . ' INR', 1, 1);

            // Payment Details Section
            $pdf->Ln(2);
            $pdf->SetFont('Arial', 'B', 12); // Larger font for section titles
            $pdf->Cell(0, 7, 'Payment Details', 1, 1, 'L', true);
            $pdf->SetFont('Arial', '', 11); // Slightly larger font
            $pdf->Cell(50, 7, 'Payment By:', 1, 0);
            $pdf->Cell(0, 7, $receipt['payment_by'], 1, 1);
            $pdf->Cell(50, 7, 'Received By:', 1, 0);
            $pdf->Cell(0, 7, $receipt['received'], 1, 1);
            $pdf->Cell(50, 7, 'Type of Payment:', 1, 0);
            $pdf->Cell(0, 7, $receipt['type_of_received'], 1, 1);

            // Footer Section
            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'I', 10); // Larger font for footer
            $pdf->Cell(0, 6, 'Authorized Signatory', 0, 1, 'C');
            $pdf->Ln(2);
            $pdf->Cell(0, 6, 'Thank you for your membership!', 0, 1, 'C');

            // Output the PDF
            $pdf->Output('D', 'receipt_' . $receipt['receipt_no'] . '.pdf');
            exit;

        } else {
            echo "Receipt not found.";
        }
    } else {
        echo "Invalid receipt number!";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
