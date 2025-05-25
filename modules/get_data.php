<?php
session_start();

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Get the request method and action
$method = $_SERVER['REQUEST_METHOD'];
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Function to get user ID from session/request
function getCurrentUserId() {
    if (isset($_GET['user_id'])) {
        return $_GET['user_id'];
    }
    // Optional: return null or handle the missing ID as needed
    return null;
}


// Function to read JSON file
function readJsonFile($filename) {
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        return json_decode($content, true) ?: [];
    }
    return [];
}

// Function to write JSON file
function writeJsonFile($filename, $data) {
    return file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

// Function to filter tasks by user
function filterTasksByUser($tasks, $userId) {
    return array_filter($tasks, function($task) use ($userId) {
        return isset($task['user']) && $task['user'] === $userId;
    });
}

switch ($action) {
    case 'get_tasks':
        $tasks = readJsonFile('../data/task.json');
        $userId = getCurrentUserId();
        $userTasks = filterTasksByUser($tasks, $userId);
        echo json_encode(array_values($userTasks));
        break;
        
    case 'get_subtasks':
        $subtasks = readJsonFile('../data/task_subtask.json');
        echo json_encode($subtasks);
        break;
        
    case 'save_task':
        if ($method === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            
            if (!$input) {
                http_response_code(400);
                echo json_encode(['error' => 'Invalid JSON data']);
                break;
            }
            
            $tasks = readJsonFile('../data/task.json');
            $userId = $_SESSION['user']['id'];
            
            // Add user ID to the task
            $input['user'] = $userId;
            
            if (isset($input['id']) && !empty($input['id'])) {
                // Update existing task
                $updated = false;
                for ($i = 0; $i < count($tasks); $i++) {
                    if ($tasks[$i]['id'] === $input['id'] && $tasks[$i]['user'] === $userId) {
                        $tasks[$i] = $input;
                        $updated = true;
                        break;
                    }
                }
                
                if (!$updated) {
                    http_response_code(404);
                    echo json_encode(['error' => 'Task not found or access denied']);
                    break;
                }
            } else {
                // Create new task with unique ID
                $input['id'] = 't' . time() . rand(1000, 9999);
                $input['approved'] = 'pending';
                $tasks[] = $input;
            }
            
            if (writeJsonFile('../data/task.json', $tasks)) {
                echo json_encode(['success' => true, 'task' => $input]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Failed to save task']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    case 'delete_task':
        if ($method === 'DELETE' || $method === 'POST') {
            $taskId = isset($_GET['task_id']) ? $_GET['task_id'] : '';
            $userId = getCurrentUserId();
            
            if (empty($taskId)) {
                http_response_code(400);
                echo json_encode(['error' => 'Task ID required']);
                break;
            }
            
            $tasks = readJsonFile('../data/task.json');
            $initialCount = count($tasks);
            
            // Remove task only if it belongs to the current user
            $tasks = array_filter($tasks, function($task) use ($taskId, $userId) {
                return !($task['id'] === $taskId && $task['user'] === $userId);
            });
            
            if (count($tasks) < $initialCount) {
                if (writeJsonFile('../data/task.json', array_values($tasks))) {
                    echo json_encode(['success' => true]);
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => 'Failed to delete task']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Task not found or access denied']);
            }
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}
?>