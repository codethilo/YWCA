<?php
// Database connection (replace with your own credentials)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ywca";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to validate date format (Y-m-d)
function validate_date($date) {
    $format = 'Y-m-d';
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and validate form inputs
    $membership_type = $conn->real_escape_string($_POST['membership_type']);
    $member_type = $conn->real_escape_string($_POST['member_type']);
    $member_name = $conn->real_escape_string($_POST['member_name']);
    $father_husband_name = $conn->real_escape_string($_POST['father_husband_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $member_fee = isset($_POST['member_fee']) ? (float)$_POST['member_fee'] : 0;
    $joining_fee = isset($_POST['joining_fee']) ? (float)$_POST['joining_fee'] : 0;
    $total_amount = $conn->real_escape_string($_POST['total_amount']);
    $service_tax = isset($_POST['service_tax']) ? 1 : 0;
    $payment_by = $conn->real_escape_string($_POST['payment_by']);
    $received_date = validate_date($_POST['received_date']) ? $conn->real_escape_string($_POST['received_date']) : null;
    $dob = validate_date($_POST['dob']) ? $conn->real_escape_string($_POST['dob']) : null;
    $date_of_joining = validate_date($_POST['date_of_joining']) ? $conn->real_escape_string($_POST['date_of_joining']) : null;
    $occupation = $conn->real_escape_string($_POST['occupation']);
    $email = $conn->real_escape_string($_POST['email']);
    $mobile_no = $conn->real_escape_string($_POST['mobile_no']);
    $office_no = !empty($_POST['office_no']) ? $conn->real_escape_string($_POST['office_no']) : null;
    $received = $conn->real_escape_string($_POST['received']);
    $type_of_received = isset($_POST['type_of_received']) ? implode(", ", $_POST['type_of_received']) : null;

    // Validation for mandatory fields
    if (!$membership_type || !$member_type || !$member_name || !$email || !$mobile_no || !$received_date || !$dob || !$date_of_joining) {
        echo "<script>alert('Please fill in all required fields.');</script>";
        exit;
    }

    // Check for duplicate entries
    $duplicate_check_query = "SELECT COUNT(*) AS count FROM receipt_entries1 WHERE email = ? OR mobile_no = ?";
    $stmt_check = $conn->prepare($duplicate_check_query);

    if ($stmt_check) {
        $stmt_check->bind_param("ss", $email, $mobile_no);
        $stmt_check->execute();
        $stmt_check->bind_result($count);
        $stmt_check->fetch();
        $stmt_check->close();

        if ($count > 0) {
            echo "<script>alert('Duplicate entry found. Member with this email or mobile number already exists.');</script>";
        } else {
            // Insert data into the database
            $insert_query = "
                INSERT INTO receipt_entries1 (
                    membership_type, member_type, member_name, father_husband_name, 
                    address, member_fee, joining_fee, total_amount, service_tax, 
                    payment_by, received_date, email, mobile_no, dob, 
                    date_of_joining, occupation, office_no, received, type_of_received
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";

            $stmt = $conn->prepare($insert_query);

            if ($stmt) {
                $stmt->bind_param(
                    "ssssssdidsdssssssss",
                    $membership_type, $member_type, $member_name, $father_husband_name,
                    $address, $member_fee, $joining_fee, $total_amount, $service_tax,
                    $payment_by, $received_date, $email, $mobile_no, $dob,
                    $date_of_joining, $occupation, $office_no, $received, $type_of_received
                );

                if ($stmt->execute()) {
                    echo "<script>alert('Record inserted successfully!');</script>";
                } else {
                    echo "<script>alert('Error: " . $stmt->error . "');</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Error in preparing the statement: " . $conn->error . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Error in preparing duplicate check query: " . $conn->error . "');</script>";
    }
}

// Get the next receipt number
$query = "SELECT MAX(receipt_no) AS last_receipt FROM receipt_entries1";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_receipt = $row['last_receipt'];
    $receipt_no = $last_receipt ? $last_receipt + 1 : 1;
} else {
    $receipt_no = 1;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt Entry Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
       body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    background: url('img/Receiptimg.jpg') no-repeat center center fixed; /* Replace with your image path */
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

.card {
    border-radius: 15px;
    background: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
    max-width: 600px;
    width: 100%;
    padding: 20px;
    overflow: hidden;
    margin: auto;
}

.card-header {
    font-size: 1.75rem;
    font-weight: bold;
    text-align: center;
    background: linear-gradient(145deg, rgb(24, 34, 66), rgb(13, 29, 75));
    color: #fff;
    padding: 15px;
    border-radius: 15px 15px 0 0;
    box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.2);
}

.card-body {
    padding: 20px;
}

.form-label {
    font-weight: bold;
    font-size: 1rem;
    color: #495057;
    margin-bottom: 5px;
    display: block;
}

.form-control {
    background: #f8f9fa;
    border: 2px solid #ced4da;
    border-radius: 10px;
    padding: 10px;
    font-size: 1rem;
    width: 100%;
    box-shadow: inset 3px 3px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
}

.form-control:focus {
    border-color: rgb(16, 32, 78);
    box-shadow: 0 0 5px rgba(78, 115, 223, 0.5);
    outline: none;
}

.form-control:invalid {
    border-color: rgb(54, 11, 15);
}

.form-control:valid {
    border-color: rgb(15, 65, 27);
}

.btn {
    display: block;
    width: 100%;
    text-transform: uppercase;
    font-weight: bold;
    padding: 12px;
    border-radius: 10px;
    border: none;
    background: linear-gradient(145deg,rgb(11, 28, 77),rgb(5, 32, 114));
    color: #fff;
    box-shadow: 3px 3px 8px rgba(0, 0, 0, 0.2);
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    margin-top: 15px;
}

.btn:hover {
    background: linear-gradient(145deg,rgb(57, 88, 156),rgb(34, 51, 90));
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.3);
    transform: scale(1.02);
}

.btn:active {
    transform: scale(0.98);
}

.small-text {
    font-size: 0.875rem;
    color: #6c757d;
    text-align: center;
    margin-top: 10px;

}

input[type="checkbox"] {
    width: 20px; 
    height: 20px;
    accent-color:rgb(9, 18, 44);
    cursor: pointer;
    margin-right: 10px; 
}

input[type="checkbox"]:focus {
    outline: 2px solid rgba(12, 34, 100, 0.8); /* Add focus indicator */
    outline-offset: 2px;
}

    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white text-center">
            Receipt Entry Form
        </div>
        <div class="card-body">
            <form method="POST">
                <!-- Receipt Number and Membership Type -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="receipt_no" class="form-label">Receipt No:</label>
                        <input type="text" class="form-control" id="receipt_no" name="receipt_no" value="<?php echo isset($receipt_no) ? $receipt_no : ''; ?>" required readonly disabled>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="membership_type" class="form-label">Membership Type:</label>
                        <select name="membership_type" id="membership_type" class="form-control" required>
                            <option value="" disabled selected>Select Membership Type</option>
                            <option value="Electoral">Electoral</option>
                            <option value="Associative">Associative</option>
                        </select>
                    </div>
                </div>

                <!-- Member Type -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="member_type" class="form-label">Member Type:</label>
                        <select name="member_type" class="form-control" id="member_type" required>
                            <option value="" disabled selected>Select Member Type</option>
                            <option value="New">New</option>
                            <option value="Renewal">Renewal</option>
                        </select>
                    </div>
                </div>

                <!-- Member Name and Father/Husband Name -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="member_name" class="form-label">Member Name:</label>
                        <input type="text" class="form-control" id="member_name" name="member_name" placeholder="Enter Member Name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="father_husband_name" class="form-label">Father/Husband Name:</label>
                        <input type="text" class="form-control" id="father_husband_name" name="father_husband_name" placeholder="Enter Name" required>
                    </div>
                </div>

                <!-- Address -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="address" class="form-label">Address:</label>
                        <textarea class="form-control" id="address" name="address" rows="2" placeholder="Enter Address" required></textarea>
                    </div>
                </div>

                <!-- Fees -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="member_fee" class="form-label">Member Fee:</label>
                        <input type="number" class="form-control" id="member_fee" name="member_fee" placeholder="Enter Fee" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="joining_fee" class="form-label">Joining Fee:</label>
                        <input type="number" class="form-control" id="joining_fee" name="joining_fee" placeholder="Enter Joining Fee" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="total_amount" class="form-label">Total Amount:</label>
                        <input type="number" class="form-control" id="total_amount" name="total_amount" placeholder="Total Amount" readonly>
                    </div>
                </div>

                <!-- Service Tax and Payment -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="service_tax" class="form-label">Service Tax:</label>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="service_tax" name="service_tax">
                            <label class="form-check-label" for="service_tax">Apply Service Tax</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="payment_by" class="form-label">Payment By:</label>
                        <input type="text" class="form-control" id="payment_by" name="payment_by" placeholder="e.g., Cash, Cheque" required>
                    </div>
                </div>

                <!-- Received Date -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="received_date" class="form-label">Received Date:</label>
                        <input type="date" class="form-control" id="received_date" name="received_date" required>
                    </div>
                </div>

                <!-- Email and Mobile -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Address" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="mobile_no" class="form-label">Mobile No:</label>
                        <input type="tel" class="form-control" id="mobile_no" name="mobile_no" placeholder="Enter Mobile Number" required>
                    </div>
                </div>

                <!-- DOB, Joining Date, Occupation -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="dob" class="form-label">Date of Birth:</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="date_of_joining" class="form-label">Date of Joining:</label>
                        <input type="date" class="form-control" id="date_of_joining" name="date_of_joining" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="occupation" class="form-label">Occupation:</label>
                        <input type="text" class="form-control" id="occupation" name="occupation" placeholder="Enter Occupation" required>
                    </div>
                </div>

                <!-- Office No -->
                <div class="mb-3">
                    <label for="office_no" class="form-label">Office No:</label>
                    <input type="text" class="form-control" id="office_no" name="office_no">
                </div>

                <!-- Received By and Type of Received -->
                <div class="mb-3">
                    <label for="received" class="form-label">Received By:</label>
                    <input type="text" class="form-control" id="received" name="received" required>
                </div>
                <div class="mb-3">
                    <label for="type_of_received" class="form-label">Type of Received:</label>
                    <select multiple class="form-control" id="type_of_received" name="type_of_received[]">
                        <option value="Cheque">Cheque</option>
                        <option value="Cash">Cash</option>
                        <option value="Online">Online</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">Submit</button>
                    <a href="receipt_db.php" class="btn btn-outline-primary">Show Information</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
//Automatically calculate total amount
    $(document).ready(function() {
        function calculateTotal() {
            let memberFee = parseFloat($('#member_fee').val()) || 0;
            let joiningFee = parseFloat($('#joining_fee').val()) || 0;
            let serviceTax = $('#service_tax').is(':checked') ? 0.18 : 0;
            let total = (memberFee + joiningFee) * (1 + serviceTax);
            $('#total_amount').val(total.toFixed(2));
        }

        $('#member_fee, #joining_fee').on('input', function() {
            calculateTotal();
        });

        $('#service_tax').on('change', function() {
            calculateTotal();
        });
    });
</script>
</body>
</html>
