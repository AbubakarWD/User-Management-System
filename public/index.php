<?php
require_once '../app/controllers/TaskController.php';
require_once '../app/models/User.php';
require_once '../app/models/Task.php';

$controller = new TaskController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? 'pending';
    $userEmail = $_POST['userEmail'] ?? '';

    // Check if user exists or create new user
    $conn = $controller->getConnection();  // Updated line (was $controller->db->getConnection())
    $stmt = $conn->prepare("SELECT id, name, email FROM users WHERE email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $userObj = new User($user['id'], $user['name'], $user['email']);
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
        $defaultName = explode('@', $userEmail)[0]; // Simple name from email
        $stmt->bind_param("ss", $defaultName, $userEmail);
        $stmt->execute();
        $userId = $conn->insert_id;
        $userObj = new User($userId, $defaultName, $userEmail);
    }

    // Create task
    $task = new Task(null, $title, $description, $status, $userObj);
    if ($controller->createTask($task)) {
        $message = "Task created successfully!";
    } else {
        $message = "Failed to create task.";
    }
}

// List tasks
$tasks = $controller->listTasks();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Task System Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <section class="container mt-5 p-3 bg-light">
        <div class="container mt-5">
            <h1>Task List</h1>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Assigned User</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?php echo $task->getId(); ?></td>
                            <td><?php echo $task->getTitle(); ?></td>
                            <td><?php echo $task->getDescription(); ?></td>
                            <td><?php echo $task->getStatus(); ?></td>
                            <td><?php echo $task->getAssignedUser()->getName(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="form-section">
            <form class="container mt-5" method="post" action="index.php">
                <div class="row">
                    <div class="mb-3 col-md-2"><label for="taskId" class="form-label">ID</label><input type="number" class="form-control" id="taskId" name="taskId" readonly placeholder="System Defined"></div>
                    <div class="mb-3 col-md-2"><label for="title" class="form-label">Title</label><input type="text" class="form-control" id="title" name="title" required></div>
                    <div class="mb-3 col-md-4"><label for="description" class="form-label">Description</label><input type="text" class="form-control" id="description" name="description" required></div>
                    <div class="mb-3 col-md-2"><label for="status" class="form-label">Status</label><select class="form-control" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                        </select></div>
                    <div class="mb-3 col-md-2"><label for="userEmail" class="form-label">Assigned User</label><input type="text" class="form-control" id="userEmail" name="userEmail" placeholder="user@example.com" required></div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </section>
</body>

</html>