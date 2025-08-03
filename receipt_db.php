<?php
$host = 'localhost';
$db = 'ywca';
$user = 'root'; // Adjust based on your database setup
$pass = '';

try {
    // Enable error reporting during development
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              // Handle search
$search = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $query = "SELECT * FROM receipt_entries1 
              WHERE receipt_no LIKE :search_query 
              OR membership_type LIKE :search_query 
              OR member_type LIKE :search_query 
              OR member_name LIKE :search_query 
              OR father_husband_name LIKE :search_query 
              OR address LIKE :search_query";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':search_query', '%' . $search . '%', PDO::PARAM_STR);
} else {
    // Default query to fetch all members
    $query = "SELECT * FROM receipt_entries1";
    $stmt = $pdo->prepare($query);
}

// Execute the query
$stmt->execute();

// Fetch the results
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
    // Delete receipt logic
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];

        // Validate delete_id to prevent SQL injection
        if (filter_var($delete_id, FILTER_VALIDATE_INT)) {
            $stmt = $pdo->prepare("DELETE FROM receipt_entries1 WHERE receipt_no = :receipt_no");
            $stmt->bindParam(':receipt_no', $delete_id, PDO::PARAM_INT);
            $stmt->execute();

            header('Location: ' . $_SERVER['PHP_SELF']); // Reload the page after deletion
            exit;
        } else {
            echo "Invalid ID!";
            exit;
        }
    }

    // Edit receipt logic
    if (isset($_POST['edit_member'])) {
        // Validate and sanitize input
        $receipt_no = filter_var($_POST['receipt_no'], FILTER_VALIDATE_INT);
        $membership_type = htmlspecialchars($_POST['membership_type'], ENT_QUOTES, 'UTF-8');
        $member_type = htmlspecialchars($_POST['member_type'], ENT_QUOTES, 'UTF-8');
        $member_name = htmlspecialchars($_POST['member_name'], ENT_QUOTES, 'UTF-8');
        $father_husband_name = htmlspecialchars($_POST['father_husband_name'], ENT_QUOTES, 'UTF-8');
        $address = htmlspecialchars($_POST['address'], ENT_QUOTES, 'UTF-8');
        $member_fee = filter_var($_POST['member_fee'], FILTER_VALIDATE_FLOAT);
        $joining_fee = filter_var($_POST['joining_fee'], FILTER_VALIDATE_FLOAT);
        $service_tax = isset($_POST['service_tax']) ? 1 : 0;
        $payment_by = htmlspecialchars($_POST['payment_by'], ENT_QUOTES, 'UTF-8');
        $received_date = htmlspecialchars($_POST['received_date'], ENT_QUOTES, 'UTF-8');
        $total_amount = filter_var($_POST['total_amount'], FILTER_VALIDATE_FLOAT);
        $dob = htmlspecialchars($_POST['dob'], ENT_QUOTES, 'UTF-8');
        $date_of_joining = htmlspecialchars($_POST['date_of_joining'], ENT_QUOTES, 'UTF-8');
        $occupation = htmlspecialchars($_POST['occupation'], ENT_QUOTES, 'UTF-8');
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $mobile_no = htmlspecialchars($_POST['mobile_no'], ENT_QUOTES, 'UTF-8');
        $office_no = htmlspecialchars($_POST['office_no'], ENT_QUOTES, 'UTF-8');
        $received = htmlspecialchars($_POST['received'], ENT_QUOTES, 'UTF-8');
        $type_of_received = htmlspecialchars($_POST['type_of_received'], ENT_QUOTES, 'UTF-8');

        // Ensure that required fields are not empty
        if ($receipt_no && $member_name && $email && $received_date) {
            $stmt = $pdo->prepare("UPDATE receipt_entries1 SET
                 membership_type = :membership_type, 
                member_type = :member_type,
                member_name = :member_name,
                father_husband_name = :father_husband_name,
                address = :address,
                member_fee = :member_fee,
                joining_fee = :joining_fee,
                service_tax = :service_tax,
                payment_by = :payment_by,
                received_date = :received_date,
                total_amount = :total_amount,
                dob = :dob,
                date_of_joining = :date_of_joining,
                occupation = :occupation,
                email = :email,
                mobile_no = :mobile_no,
                office_no = :office_no,
                received = :received,
                type_of_received = :type_of_received
                WHERE receipt_no = :receipt_no");

            $stmt->bindParam(':receipt_no', $receipt_no);
            $stmt->bindParam(':membership_type', $membership_type);
            $stmt->bindParam(':member_type', $member_type);
            $stmt->bindParam(':member_name', $member_name);
            $stmt->bindParam(':father_husband_name', $father_husband_name);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':member_fee', $member_fee);
            $stmt->bindParam(':joining_fee', $joining_fee);
            $stmt->bindParam(':service_tax', $service_tax);
            $stmt->bindParam(':payment_by', $payment_by);
            $stmt->bindParam(':received_date', $received_date);
            $stmt->bindParam(':total_amount', $total_amount);
            $stmt->bindParam(':dob', $dob);
            $stmt->bindParam(':date_of_joining', $date_of_joining);
            $stmt->bindParam(':occupation', $occupation);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':mobile_no', $mobile_no);
            $stmt->bindParam(':office_no', $office_no);
            $stmt->bindParam(':received', $received);
            $stmt->bindParam(':type_of_received', $type_of_received);

            $stmt->execute();

            header('Location: ' . $_SERVER['PHP_SELF']); // Reload the page after editing
            exit;
        } else {
            echo "Please fill in all required fields!";
        }
    }
} catch (PDOException $e) {
    // If the connection fails, display an error message
    echo 'Connection failed: ' . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipts List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- FontAwesome for icons -->
    <style>
        body {
    background: url('img/data.jpg') no-repeat center center fixed; /* Background image */
    background-size: cover;
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 5px;;
    color: #495057;
}

.container {
    margin-top: 3rem;
    padding: 20px;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    background-color: rgba(255, 255, 255, 0.5); /* Semi-transparent background */
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
}

.table {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    width: 100%;
    border-collapse: collapse;
    overflow: hidden;
}

.table th, .table td {
    padding: 12px 20px;
    text-align: center;
    font-size: 1rem;
}

.table th {
    background-color: rgb(6, 16, 46); /* Dark blue background for header */
    color: white;
    font-weight: bold;
}

.table td {
    background-color: #f8f9fa; /* Light background for table rows */
    transition: background-color 0.3s ease;
}

.table td:hover {
    background-color: #e9ecef; /* Hover effect for rows */
}

.table td .btn {
    border-radius: 50%;
    padding: 0.75rem;
    font-size: 1rem;
    transition: transform 0.3s ease;
}

.table .btn:hover {
    transform: scale(1.1);
}

.table .btn-edit {
    background-color: #ffc107;
    color: white;
}

.table .btn-edit:hover {
    background-color: #e0a800;
}

.table .btn-delete {
    background-color: #dc3545;
    color: white;
}

.table .btn-delete:hover {
    background-color: #c82333;
}

.table .btn-info {
    background-color: #17a2b8;
    color: white;
}

.table .btn-info:hover {
    background-color: #138496;
}

    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center my-4">Receipts List</h2>
    <!-- Search form -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Name, Email, Phone, or Occupation" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
        <!-- Back button -->
    <a href="form.php" class="btn btn-secondary mt-2">
        <i class="fas fa-arrow-left"></i> Back
    </a>
    <!-- Download Excel Button -->
<a href="download_excel.php" class="btn btn-primary mt-2">
    Download Excel Receipt
</a>
         </form>
         


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Receipt No</th>
                <th>Membership Type</th>
                <th>Member Type</th>
                <th>Member Name</th>
                <th>Father/Husband Name</th>
                <th>Address</th>
                <th>Member Fee</th>
                <th>Joining Fee</th>
                <th>Service Tax</th>
                <th>Payment By</th>
                <th>Received Date</th>
                <th>Total Amount</th>
                <th>Date of Birth</th>
                <th>Date of Joining</th>
                <th>Occupation</th>
                <th>Email</th>
                <th>Mobile No</th>
                <th>Office No</th>
                <th>Received</th>
                <th>Type of Received</th>
                <th>Actions</th> <!-- Column for edit and delete buttons -->
            </tr>
        </thead>
        <tbody>
        <?php if ($members): ?>

            <?php foreach ($members as $receipt): ?>

                    <tr>
                        <td><?php echo htmlspecialchars($receipt['receipt_no']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['membership_type']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['member_type']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['member_name']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['father_husband_name']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['address']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['member_fee']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['joining_fee']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['service_tax']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['payment_by']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['received_date']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['total_amount']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['dob']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['date_of_joining']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['occupation']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['email']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['mobile_no']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['office_no']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['received']); ?></td>
                        <td><?php echo htmlspecialchars($receipt['type_of_received']); ?></td>
                        <!-- Inside the table row -->
<td>
    <!-- Edit button -->
    <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editModal_<?php echo $receipt['receipt_no']; ?>"><i class="fas fa-edit"></i></button>

    <!-- Delete button -->
    <a href="?delete_id=<?php echo $receipt['receipt_no']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this record?')"><i class="fas fa-trash"></i></a>

    <!-- Print button (added receipt_no as query parameter) -->
    <a href="pdf_receipt.php?receipt_no=<?php echo $receipt['receipt_no']; ?>" target="_blank" class="btn btn-info" title="Print Receipt">
        <i class="fas fa-print"></i>
    </a>
</td>
                    </tr>

                    <!-- Edit Modal -->
                    <div class="modal fade" id="editModal_<?php echo $receipt['receipt_no']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Receipt - <?php echo $receipt['receipt_no']; ?></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="">
                                        <input type="hidden" name="receipt_no" value="<?php echo $receipt['receipt_no']; ?>">

                                        <!-- Fields to edit receipt details -->
                                        <div class="mb-3">
                                            <label for="membership_type" class="form-label">Membership Type</label>
                                            <input type="text" class="form-control" name="membership_type" value="<?php echo $receipt['membership_type']; ?>">
                                        </div>
                                        <div class="mb-3">
                                            <label for="member_type" class="form-label">Member Type</label>
                                            <input type="text" class="form-control" name="member_type" value="<?php echo $receipt['member_type']; ?>">
                                        </div>
                                       

                                        <div class="mb-3">
                                            <label for="member_name" class="form-label">Member Name</label>
                                            <input type="text" class="form-control" name="member_name" value="<?php echo $receipt['member_name']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="father_husband_name" class="form-label">Father/Husband Name</label>
                                            <input type="text" class="form-control" name="father_husband_name" value="<?php echo $receipt['father_husband_name']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <input type="text" class="form-control" name="address" value="<?php echo $receipt['address']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="member_fee" class="form-label">Member Fee</label>
                                            <input type="text" class="form-control" name="member_fee" value="<?php echo $receipt['member_fee']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="joining_fee" class="form-label">Joining Fee</label>
                                            <input type="text" class="form-control" name="joining_fee" value="<?php echo $receipt['joining_fee']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="service_tax" class="form-label">Service Tax</label>
                                            <input type="text" class="form-control" name="service_tax" value="<?php echo $receipt['service_tax']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="payment_by" class="form-label">Payment By</label>
                                            <input type="text" class="form-control" name="payment_by" value="<?php echo $receipt['payment_by']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="received_date" class="form-label">Received Date</label>
                                            <input type="date" class="form-control" name="received_date" value="<?php echo $receipt['received_date']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="total_amount" class="form-label">Total Amount</label>
                                            <input type="number" class="form-control" name="total_amount" value="<?php echo $receipt['total_amount']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="dob" class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control" name="dob" value="<?php echo $receipt['dob']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="date_of_joining" class="form-label">Date of Joining</label>
                                            <input type="date" class="form-control" name="date_of_joining" value="<?php echo $receipt['date_of_joining']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="occupation" class="form-label">Occupation</label>
                                            <input type="text" class="form-control" name="occupation" value="<?php echo $receipt['occupation']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo $receipt['email']; ?>">
                                        </div>

                                        
                                        <div class="mb-3">
                                            <label for="mobile_no" class="form-label">Mobile No</label>
                                            <input type="text" class="form-control" name="mobile_no" value="<?php echo $receipt['mobile_no']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="office_no" class="form-label">Office No</label>
                                            <input type="text" class="form-control" name="office_no" value="<?php echo $receipt['office_no']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="received" class="form-label">Received</label>
                                            <input type="text" class="form-control" name="received" value="<?php echo $receipt['received']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <label for="type_of_received" class="form-label">Type of Received</label>
                                            <input type="text" class="form-control" name="type_of_received" value="<?php echo $receipt['type_of_received']; ?>">
                                        </div>

                                        <div class="mb-3">
                                            <button type="submit" name="edit_member" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="19" class="text-center">No records found</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
