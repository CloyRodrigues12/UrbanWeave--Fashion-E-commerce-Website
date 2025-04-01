<?php
session_start();
include('db.php'); // Include database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: indexpg.php"); // Redirect to home if not admin
    exit();
}

// Handle employee addition
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_employee'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $hire_date = mysqli_real_escape_string($conn, $_POST['hire_date']);
    $salary = mysqli_real_escape_string($conn, $_POST['salary']);

    $query = "INSERT INTO employees (first_name, last_name, email, position, hire_date, salary) VALUES ('$first_name', '$last_name', '$email', '$position', '$hire_date', '$salary')";
    
    if (mysqli_query($conn, $query)) {
        echo "New employee added successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Fetch employee data from the database
$query = "SELECT * FROM employees";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="icon" href="Slogo.UrbanWeave.png">
    <title>UrbanWeave - Employee Info</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Inline styling for demonstration; move to external stylesheet later */
        .form-container {
            display: none; /* Initially hidden */
            width: 80%;
            margin: auto;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .form-container label {
            font-weight: bold;
        }

        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="date"],
        .form-container input[type="number"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container input[type="submit"] {
            background-color: rgb(71, 121, 104);
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<br><br>

<h1 align="center">Employee Info</h1> <br>
<!-- Add Employee Button -->
<button align="center" id="addEmployeeBtn">Add Employee</button> <br>

<!-- Add Employee Form -->
<div class="form-container" id="employeeForm" style="width: 80%; margin: auto; border-collapse: collapse; text-align: left; margin-top: 20px;">
    <h2>Add Employee</h2> <br>
    <form action="add_employee.php" method="POST">
        <label for="firstName">First Name:</label>
        <input type="text" id="firstName" name="firstName" required>

        <label for="lastName">Last Name:</label>
        <input type="text" id="lastName" name="lastName" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required>

        <label for="salary">Salary:</label>
        <input type="number" id="salary" name="salary" required>

        <input type="submit" value="Add Employee">
    </form>
</div>

<!-- JavaScript to toggle the form -->
<script>
    document.getElementById('addEmployeeBtn').addEventListener('click', function() {
        const form = document.getElementById('employeeForm');
        form.style.display = form.style.display === 'none' || form.style.display === '' ? 'block' : 'none';
    });
</script>


<!-- Employee Table -->
<table style="width: 80%; margin: auto; border-collapse: collapse; text-align: left; margin-top: 20px;">
    <thead>
        <tr style="background-color: rgb(71, 121, 104); color: white;">
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Position</th>
            <th>Hire Date</th>
            <th>Salary</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr style='border-bottom: 1px solid #ddd;'>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . htmlspecialchars($row['first_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['last_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['position']) . "</td>";
                echo "<td>" . htmlspecialchars($row['hire_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['salary']) . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>No employees found.</td></tr>";
        }
        ?>
    </tbody>
</table>



<br><br><br><br>
<?php include 'footer.php'; ?>
</body>
</html>
