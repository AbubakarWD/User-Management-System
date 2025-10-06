<?php

require_once '../app/controllers/TaskController.php';
// Demo data
$controller = new TaskController();

// Create a user
$user = new User(null, 'John Doe', 'john@example.com');

// Create a task
$task = new Task(null, 'Sample Task', 'This is a description', 'pending', $user);
$controller->createTask($task);

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
</body>

</html>