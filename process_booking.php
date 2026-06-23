<?php
require_once 'config.php';
require_once('fpdf.php'); // download FPDF and place in same folder

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $event_date = $_POST['event_date'];
    $event_type = $_POST['event_type'];
    $guest_count = $_POST['guest_count'];
    $decor_theme = $_POST['decor_theme'];
    $catering_package = $_POST['catering_package'];

    // Calculate price
    $base = 0;
    switch($event_type) {
        case 'wedding': $base = 5000; break;
        case 'corporate': $base = 3000; break;
        case 'private': $base = 2000; break;
        case 'kids': $base = 1500; break;
    }
    $total_price = $base + ($guest_count * 150);

    // Save to database
    $stmt = $pdo->prepare("INSERT INTO bookings (guest_name, guest_email, guest_phone, event_type, event_date, guest_count, decor_theme, catering_package, total_price, deposit_paid, balance_due, status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
    $deposit = $total_price * 0.5;
    $balance = $total_price - $deposit;
    $stmt->execute([$name, $email, $phone, $event_type, $event_date, $guest_count, $decor_theme, $catering_package, $total_price, $deposit, $balance, 'pending']);
    $booking_id = $pdo->lastInsertId();

    // Generate PDF (simplified)
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf->Cell(40,10,'Senzakwenzeke Quote');
    $pdf->Ln();
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,10,"Client: $name");
    $pdf->Ln();
    $pdf->Cell(0,10,"Event Date: $event_date");
    $pdf->Ln();
    $pdf->Cell(0,10,"Guest Count: $guest_count");
    $pdf->Ln();
    $pdf->Cell(0,10,"Total Price: R $total_price");
    $pdf->Ln();
    $pdf->Cell(0,10,"Deposit (50%): R $deposit");
    $pdf->Ln();
    $pdf->Cell(0,10,"Balance Due: R $balance");
    $pdf->Output('F', "quotes/quote_$booking_id.pdf");

    // Send email (using mail() for demo)
    $to = $email;
    $subject = "Your Quote from Senzakwenzeke";
    $message = "Dear $name,\n\nThank you for your enquiry. Your quote total is R $total_price.\nPlease find attached PDF.\n\nRegards,\nSenzakwenzeke Team";
    $headers = "From: quotes@senzakwenzeke.co.za";
    mail($to, $subject, $message, $headers);

    // Also send to admin
    mail("admin@senzakwenzeke.co.za", "New booking #$booking_id", "Booking from $name", $headers);

    // Redirect to success page
    header("Location: quote_success.php?id=$booking_id");
    exit;
}
?>