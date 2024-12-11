<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'resume_db');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $bio = htmlspecialchars($_POST['bio']);
    $resumePath = '';

    // Handle file upload
    if (isset($_FILES['resume']) && $_FILES['resume']['error'] == UPLOAD_ERR_OK) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // Create directory if it doesn't exist
        }

        $resumePath = $targetDir . time() . "_" . basename($_FILES["resume"]["name"]);
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $resumePath)) {
            $resumePath = htmlspecialchars($resumePath);
        } else {
            echo "<p>Error uploading the resume file.</p>";
            $resumePath = '';
        }
    }

    // Insert data into the database
    if (!empty($name) && !empty($email) && !empty($phone) && !empty($bio) && !empty($resumePath)) {
        $sql = "INSERT INTO resumes (name, email, phone, bio, resume) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $name, $email, $phone, $bio, $resumePath);
            if ($stmt->execute()) {
                echo "<p>Data successfully added!</p>";
            } else {
                echo "<p>Error inserting data: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p>Error preparing SQL: " . $conn->error . "</p>";
        }
    } else {
        echo "<p>All fields are required.</p>";
    }
}

// Fetch and display data
$sql = "SELECT * FROM resumes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Display table header
    echo "<table border='1' style='width:100%; text-align:left; border-collapse:collapse;'>";
    echo "<tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Biodata</th>
            <th>Resume</th>
          </tr>";

    // Loop through each record
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['phone']) . "</td>
                <td>" . nl2br(htmlspecialchars($row['bio'])) . "</td>
                <td><a href='" . htmlspecialchars($row['resume']) . "' target='_blank'>Download</a></td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>No records found.</p>";
}

// Close connection
$conn->close();
?>
