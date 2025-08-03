<?php
$host = 'localhost';
$db = 'ywca';
$user = 'root'; // Adjust based on your database setup
$pass = '';

try {
    // Create a PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle search
    $search = '';
    if (isset($_GET['search'])) {
        $search = trim($_GET['search']);
        $query = "SELECT * FROM members WHERE 
            name LIKE :search OR 
            email LIKE :search OR 
            phone LIKE :search OR 
            occupation LIKE :search";
        $stmt = $pdo->prepare($query);
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
    } else {
        // Default query to fetch all members
        $stmt = $pdo->query("SELECT * FROM members");
    }

    $stmt->execute();
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Delete member logic
    if (isset($_GET['delete_id'])) {
        $delete_id = $_GET['delete_id'];
        $stmt = $pdo->prepare("DELETE FROM members WHERE id = :id");
        $stmt->bindParam(':id', $delete_id, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: ' . $_SERVER['PHP_SELF']); // Reload the page after deletion
        exit;
    }

    // Edit member logic (unchanged)
    if (isset($_POST['edit_member'])) {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $dob = $_POST['dob'];
        $age_group = $_POST['age_group'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $occupation = $_POST['occupation'];
        $original_membership_year = $_POST['original_membership_year'];
        $break_year = $_POST['break_year'];
        $office_bearer = $_POST['office_bearer'];
        $board_member = $_POST['board_member'];
        $voluntary_work = $_POST['voluntary_work'];
        $interest_areas = $_POST['interest_areas'];

        $stmt = $pdo->prepare("UPDATE members SET 
            name = :name, dob = :dob, age_group = :age_group, email = :email, phone = :phone, 
            occupation = :occupation, original_membership_year = :original_membership_year, 
            break_year = :break_year, office_bearer = :office_bearer, board_member = :board_member, 
            voluntary_work = :voluntary_work, interest_areas = :interest_areas WHERE id = :id");
        
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':age_group', $age_group);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':occupation', $occupation);
        $stmt->bindParam(':original_membership_year', $original_membership_year);
        $stmt->bindParam(':break_year', $break_year);
        $stmt->bindParam(':office_bearer', $office_bearer);
        $stmt->bindParam(':board_member', $board_member);
        $stmt->bindParam(':voluntary_work', $voluntary_work);
        $stmt->bindParam(':interest_areas', $interest_areas);

        $stmt->execute();
        header('Location: ' . $_SERVER['PHP_SELF']); // Reload the page after editing
        exit;
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    padding: 5px;
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
.back-btn {
            display: inline-block;
            margin-bottom: 1rem;
            padding: 10px 20px;
            background-color:rgb(41, 8, 119); /* Dark green */
            color: #fff; /* White text */
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Light shadow */
            transition: all 0.3s ease; /* Smooth hover effect */
        }
        .back-btn:hover {
            background-color:rgb(40, 4, 108); /* Lighter green on hover */
            color: #fff; /* Keep text white */
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15); /* Slightly stronger shadow */
            transform: scale(1.05); /* Slight zoom */
        }

    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center my-4">Member List</h2>

    <!-- Search form -->
    <form method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by Name, Email, Phone, or Occupation" value="<?php echo htmlspecialchars($search); ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
        <a href="form.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
        <!-- Download Excel Button -->
          <a href="download_excel_Members.php" class="btn btn-primary mt-2">
                 Download Excel 
                 </a>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Age Group</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Occupation</th>
                <th>Original Membership Year</th>
                <th>Break Year</th>
                <th>Office Bearer</th>
                <th>Board Member</th>
                <th>Voluntary Work</th>
                <th>Interest Areas</th>
                <th>Actions</th> <!-- Column for edit and delete buttons -->
            </tr>
        </thead>
        <tbody>
            <?php if ($members): ?>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($member['name']); ?></td>
                        <td><?php echo htmlspecialchars($member['dob']); ?></td>
                        <td><?php echo htmlspecialchars($member['age_group']); ?></td>
                        <td><?php echo htmlspecialchars($member['email']); ?></td>
                        <td><?php echo htmlspecialchars($member['phone']); ?></td>
                        <td><?php echo htmlspecialchars($member['occupation']); ?></td>
                        <td><?php echo htmlspecialchars($member['original_membership_year']); ?></td>
                        <td><?php echo htmlspecialchars($member['break_year']); ?></td>
                        <td><?php echo htmlspecialchars($member['office_bearer']); ?></td>
                        <td><?php echo htmlspecialchars($member['board_member']); ?></td>
                        <td><?php echo htmlspecialchars($member['voluntary_work']); ?></td>
                        <td><?php echo htmlspecialchars($member['interest_areas']); ?></td>
                        <td>
                            <!-- Edit button with a modal trigger -->
                            <button class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $member['id']; ?>" title="Edit"><i class="fas fa-edit"></i></button>
                            <!-- Delete button -->
                            <a href="?delete_id=<?php echo $member['id']; ?>" class="btn btn-delete" title="Delete" onclick="return confirm('Are you sure you want to delete this member?');"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>

                    <!-- Modal for Edit -->
                    <div class="modal fade" id="editModal<?php echo $member['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel">Edit Member</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control" name="name" value="<?php echo $member['name']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="dob" class="form-label">Date of Birth</label>
                                            <input type="date" class="form-control" name="dob" value="<?php echo $member['dob']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="age_group" class="form-label">Age Group</label>
                                            <input type="text" class="form-control" name="age_group" value="<?php echo $member['age_group']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo $member['email']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="phone" class="form-label">Phone</label>
                                            <input type="text" class="form-control" name="phone" value="<?php echo $member['phone']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="occupation" class="form-label">Occupation</label>
                                            <input type="text" class="form-control" name="occupation" value="<?php echo $member['occupation']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="original_membership_year" class="form-label">Original Membership Year</label>
                                            <input type="number" class="form-control" name="original_membership_year" value="<?php echo $member['original_membership_year']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="break_year" class="form-label">Break Year</label>
                                            <input type="number" class="form-control" name="break_year" value="<?php echo $member['break_year']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="office_bearer" class="form-label">Office Bearer</label>
                                            <input type="text" class="form-control" name="office_bearer" value="<?php echo $member['office_bearer']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="board_member" class="form-label">Board Member</label>
                                            <input type="text" class="form-control" name="board_member" value="<?php echo $member['board_member']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="voluntary_work" class="form-label">Voluntary Work</label>
                                            <input type="text" class="form-control" name="voluntary_work" value="<?php echo $member['voluntary_work']; ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="interest_areas" class="form-label">Interest Areas</label>
                                            <input type="text" class="form-control" name="interest_areas" value="<?php echo $member['interest_areas']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="edit_member" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="13" class="text-center">No members found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
