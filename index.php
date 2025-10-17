<?php
session_start();

// التهيئة الأساسية
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// نظام التوجيه الذكي
class Router {
    private $routes = [];
    private $currentPath;
    
    public function __construct() {
        $this->currentPath = $this->getCleanPath();
    }
    
    private function getCleanPath() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = str_replace('/index.php', '', $path);
        return trim($path, '/');
    }
    
    public function route() {
        $path = $this->currentPath;
        
        // إذا كان المسار فارغاً (الصفحة الرئيسية)
        if (empty($path)) {
            $this->handleHome();
            return;
        }
        
        // إذا كان المسار يتوافق مع ملف PHP موجود
        if ($this->phpFileExists($path)) {
            $this->includePhpFile($path);
            return;
        }
        
        // إذا كان المسار يتوافق مع مسار مخصص
        if ($this->handleCustomRoute($path)) {
            return;
        }
        
        // إذا لم يتم العثور على أي مسار
        $this->show404();
    }
    
    private function phpFileExists($path) {
        $phpFile = $path . '.php';
        return file_exists($phpFile);
    }
    
    private function includePhpFile($path) {
        $phpFile = $path . '.php';
        
        // التحقق من أن الملف ضمن المسموح به
        if ($this->isAllowedFile($phpFile)) {
            include_once $phpFile);
        } else {
            $this->show404();
        }
    }
    
    private function isAllowedFile($file) {
        // منع الوصول إلى الملفات الحساسة
        $forbidden = ['config.php', 'database.php', '.env.php'];
        return !in_array(basename($file), $forbidden);
    }
    
    private function handleCustomRoute($path) {
        $routes = [
            'post-task' => ['method' => 'POST', 'handler' => 'handlePostTask'],
            'delete-task' => ['method' => 'GET', 'handler' => 'handleDeleteTask'],
            'toggle-task' => ['method' => 'GET', 'handler' => 'handleToggleTask'],
        ];
        
        if (isset($routes[$path])) {
            $route = $routes[$path];
            if ($_SERVER['REQUEST_METHOD'] === $route['method']) {
                $this->{$route['handler']}();
                return true;
            }
        }
        
        return false;
    }
    
    private function handleHome() {
        $this->renderPage('home', 'تطبيق إدارة المهام');
    }
    
    private function handlePostTask() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
            $newTask = trim($_POST['task']);
            if (!empty($newTask)) {
                $_SESSION['tasks'][] = [
                    'id' => uniqid(),
                    'text' => htmlspecialchars($newTask),
                    'completed' => false,
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        }
        header('Location: /');
        exit;
    }
    
    private function handleDeleteTask() {
        if (isset($_GET['id'])) {
            $taskId = $_GET['id'];
            $_SESSION['tasks'] = array_filter($_SESSION['tasks'], function($task) use ($taskId) {
                return $task['id'] !== $taskId;
            });
        }
        header('Location: /');
        exit;
    }
    
    private function handleToggleTask() {
        if (isset($_GET['id'])) {
            $taskId = $_GET['id'];
            foreach ($_SESSION['tasks'] as &$task) {
                if ($task['id'] === $taskId) {
                    $task['completed'] = !$task['completed'];
                    break;
                }
            }
        }
        header('Location: /');
        exit;
    }
    
    private function renderPage($template, $title = '') {
        $templateFile = "templates/{$template}.php";
        if (file_exists($templateFile)) {
            include_once $templateFile;
        } else {
            $this->show404();
        }
    }
    
    private function show404() {
        header("HTTP/1.0 404 Not Found");
        $this->renderPage('404', 'الصفحة غير موجودة');
    }
}

// تشغيل النظام
$router = new Router();
$router->route();
?>
