<?php
require_once __DIR__ . '/User.php'; // Import User class
class Task
{
    private $id;
    private $title;
    private $description;
    private $status;
    private $assignedUser; // Instance of User class

    public function __construct($id, $title, $description, $status, User $assignedUser)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->assignedUser = $assignedUser;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getTitle()
    {
        return $this->title;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getAssignedUser()
    {
        return $this->assignedUser;
    }
    // Setters
    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function setDescription($description)
    {
        $this->description = $description;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function setAssignedUser(User $assignedUser)
    {
        $this->assignedUser = $assignedUser;
    }
}
