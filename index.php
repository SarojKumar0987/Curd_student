<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include "db.php"; // Include your database connection file

// Fetch student records
$sql = "SELECT * FROM `students`";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        .dropdown img {
            width: 24px;
            height: 24px;
            vertical-align: middle;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 23px;
            left: 0;
            background-color: #fff;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.2);
            z-index: 1;
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .dropdown-menu li {
            padding: 4px 4px;
        }
        .dropdown-menu li a {
            text-decoration: none;
            color: #333;
            display: block;
        }
        .dropdown-menu li a:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="heading" style="display: flex; justify-content: space-between;">
                <h3 style="color: red; font-weight: 700;"><?php echo $_SESSION['username']; ?></h3>
                <div class="right" style="padding: 11px 1px 0px; display: flex;">
                    <div class="home" style="padding: 0 25px; font-weight: 700;">
                        <a style="color: black;text-decoration: none;" href="">Home</a>
                    </div>
                    <div class="logout" style=" font-weight: 700;">
                        <a style="color: black;text-decoration: none;" href="#" id="logoutBtn">Logout</a>
                    </div>
                </div>
            </div>
            <div class="student_all" style="background-color: #ccced0;padding: 16px 9px 0px 9px;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr id="student-<?php echo $row['id']; ?>">
                            <td contenteditable="true" class="editable" data-id="<?php echo $row['id']; ?>" data-field="name"><?php echo $row['name']; ?></td>
                            <td contenteditable="true" class="editable" data-id="<?php echo $row['id']; ?>" data-field="subject"><?php echo $row['subject']; ?></td>
                            <td contenteditable="true" class="editable" data-id="<?php echo $row['id']; ?>" data-field="marks"><?php echo $row['marks']; ?></td>
                            <td>
                                <div class="dropdown">
                                    <img src="./public/dropdown-icon.webp" alt="Dropdown Icon" onclick="toggleDropdown(event, '<?php echo $row['id']; ?>')">
                                    <ul class="dropdown-menu" id="dropdownMenu-<?php echo $row['id']; ?>">
                                        <li><a href="#" onclick="openUpdateModal('<?php echo $row['id']; ?>', '<?php echo $row['name']; ?>', '<?php echo $row['subject']; ?>', '<?php echo $row['marks']; ?>')">Update</a></li>
                                        <li><a href="#" onclick="openDeleteModal('<?php echo $row['id']; ?>')">Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="student">
                    <button class="btn btn-dark my-3" style="padding: 6px 50px;" data-bs-toggle="modal" data-bs-target="#newStudentModal">Add</button>
                </div>
            </div>
        </div>
    </div>

    <!-- New Student Modal -->
    <div class="modal fade" id="newStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="newStudentForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="studentName" class="form-label">Student Name</label>
                            <input type="text" class="form-control" id="studentName" required>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="marks" class="form-label">Marks</label>
                            <input type="number" class="form-control" id="marks" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Student</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Update Student Modal -->
    <div class="modal fade" id="updateStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="updateStudentForm">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="updateStudentId">
                        <div class="mb-3">
                            <label for="updateName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="updateName" required>
                        </div>
                        <div class="mb-3">
                            <label for="updateSubject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="updateSubject" required>
                        </div>
                        <div class="mb-3">
                            <label for="updateMarks" class="form-label">Marks</label>
                            <input type="number" class="form-control" id="updateMarks" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this student?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>

function toggleDropdown(event, id) {
    event.stopPropagation();
    const menu = document.getElementById(`dropdownMenu-${id}`);
    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

window.onclick = function(event) {
    document.querySelectorAll('.dropdown-menu').forEach(menu => {
        if (!event.target.closest('.dropdown')) {
            menu.style.display = 'none';
        }
    });
}

// Add new student
document.getElementById('newStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let name = document.getElementById('studentName').value;
    let subject = document.getElementById('subject').value;
    let marks = document.getElementById('marks').value;

    fetch('add_student.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ name, subject, marks })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => console.error('Error:', error));
});

// Open update modal
function openUpdateModal(id, name, subject, marks) {
    document.getElementById('updateStudentId').value = id;
    document.getElementById('updateName').value = name;
    document.getElementById('updateSubject').value = subject;
    document.getElementById('updateMarks').value = marks;
    new bootstrap.Modal(document.getElementById('updateStudentModal')).show();
}

// Update student
document.getElementById('updateStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    let id = document.getElementById('updateStudentId').value;
    let name = document.getElementById('updateName').value;
    let subject = document.getElementById('updateSubject').value;
    let marks = document.getElementById('updateMarks').value;

    fetch('update_student.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id, name, subject, marks })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => console.error('Error:', error));
});

// Open delete modal
function openDeleteModal(id) {
    document.getElementById('confirmDeleteBtn').setAttribute('data-id', id);
    new bootstrap.Modal(document.getElementById('deleteStudentModal')).show();
}

// Delete student
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    let id = this.getAttribute('data-id');

    fetch('delete_student.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        location.reload();
    })
    .catch(error => console.error('Error:', error));
});

// Logout functionality
document.getElementById('logoutBtn').addEventListener('click', function(e) {
    e.preventDefault();
    fetch('logout.php')
        .then(() => {
            window.location.href = 'login.php';
        });
});
</script>
</body>
</html>
