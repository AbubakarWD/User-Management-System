<?php
require_once __DIR__ . '/../services/Database.php';
require_once __DIR__ . '/../models/Task.php';
require_once __DIR__ . '/../models/User.php';
class TaskController
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
    // Create task
public function createTask(Task $task) {
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare("INSERT INTO tasks (title, description, status, user_id) VALUES (?, ?, ?, ?)");
    $title = $task->getTitle();
    $description = $task->getDescription();
    $status = $task->getStatus();
    $userId = $task->getAssignedUser()->getId();
    $stmt->bind_param("sssi", $title, $description, $status, $userId);
    return $stmt->execute();
}
    // Update task
    public function updateTask(Task $task)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("UPDATE tasks SET title = ?, description = ?, status = ?, user_id = ? WHERE id = ?");
        $stmt->bind_param("sssii", $task->getTitle(), $task->getDescription(), $task->getStatus(), $task->getAssignedUser()->getId(), $task->getId());
        return $stmt->execute();
    }

    // Delete task
    public function deleteTask($id)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // List tasks
    public function listTasks()
    {
        $conn = $this->db->getConnection();
        $result = $conn->query("SELECT t.id AS task_id, t.title, t.description, t.status, u.id AS user_id, u.name, u.email 
                                FROM tasks t JOIN users u ON t.user_id = u.id");
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $user = new User($row['user_id'], $row['name'], $row['email']);
            $task = new Task($row['task_id'], $row['title'], $row['description'], $row['status'], $user);
            $tasks[] = $task;
        }
        return $tasks;
    }
}
