<?php
// تطبيق عربي بسيط لإدارة المهام
session_start();

// تهيئة قائمة المهام إذا لم تكن موجودة
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// إضافة مهمة جديدة
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
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// حذف مهمة
if (isset($_GET['delete'])) {
    $taskId = $_GET['delete'];
    $_SESSION['tasks'] = array_filter($_SESSION['tasks'], function($task) use ($taskId) {
        return $task['id'] !== $taskId;
    });
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// تحديث حالة المهمة
if (isset($_GET['toggle'])) {
    $taskId = $_GET['toggle'];
    foreach ($_SESSION['tasks'] as &$task) {
        if ($task['id'] === $taskId) {
            $task['completed'] = !$task['completed'];
            break;
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تطبيق إدارة المهام</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            direction: rtl;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
        }
        
        .header {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
        }
        
        .header p {
            opacity: 0.8;
            font-size: 1.1em;
        }
        
        .content {
            padding: 30px;
        }
        
        .add-task-form {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .task-input {
            flex: 1;
            padding: 15px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        .task-input:focus {
            outline: none;
            border-color: #3498db;
        }
        
        .add-btn {
            background: #27ae60;
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        
        .add-btn:hover {
            background: #219a52;
        }
        
        .tasks-list {
            space: 15px;
        }
        
        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            margin-bottom: 10px;
            border-right: 4px solid #3498db;
            transition: transform 0.2s;
        }
        
        .task-item:hover {
            transform: translateX(-5px);
        }
        
        .task-item.completed {
            opacity: 0.7;
            border-right-color: #27ae60;
        }
        
        .task-text {
            flex: 1;
            margin: 0 15px;
        }
        
        .task-text.completed {
            text-decoration: line-through;
            color: #666;
        }
        
        .task-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .toggle-btn {
            background: #3498db;
            color: white;
        }
        
        .toggle-btn:hover {
            background: #2980b9;
        }
        
        .delete-btn {
            background: #e74c3c;
            color: white;
        }
        
        .delete-btn:hover {
            background: #c0392b;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .empty-state i {
            font-size: 3em;
            margin-bottom: 20px;
            opacity: 0.5;
        }
        
        .stats {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 تطبيق إدارة المهام</h1>
            <p>نظم مهامك اليومية بسهولة</p>
        </div>
        
        <div class="content">
            <form method="POST" class="add-task-form">
                <input type="text" name="task" class="task-input" placeholder="أضف مهمة جديدة..." required>
                <button type="submit" class="add-btn">إضافة المهمة</button>
            </form>
            
            <div class="tasks-list">
                <?php if (empty($_SESSION['tasks'])): ?>
                    <div class="empty-state">
                        <div>📋</div>
                        <h3>لا توجد مهام حالياً</h3>
                        <p>ابدأ بإضافة مهمة جديدة باستخدام النموذج أعلاه</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($_SESSION['tasks'] as $task): ?>
                        <div class="task-item <?php echo $task['completed'] ? 'completed' : ''; ?>">
                            <div class="task-text <?php echo $task['completed'] ? 'completed' : ''; ?>">
                                <?php echo $task['text']; ?>
                                <small style="display: block; color: #888; font-size: 0.8em;">
                                    <?php echo $task['created_at']; ?>
                                </small>
                            </div>
                            <div class="task-actions">
                                <a href="?toggle=<?php echo $task['id']; ?>" class="btn toggle-btn">
                                    <?php echo $task['completed'] ? 'إلغاء' : 'إكمال'; ?>
                                </a>
                                <a href="?delete=<?php echo $task['id']; ?>" class="btn delete-btn" 
                                   onclick="return confirm('هل أنت متأكد من حذف هذه المهمة؟')">
                                    حذف
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="stats">
                <strong>إحصائيات:</strong>
                <?php
                $totalTasks = count($_SESSION['tasks']);
                $completedTasks = count(array_filter($_SESSION['tasks'], function($task) {
                    return $task['completed'];
                }));
                $pendingTasks = $totalTasks - $completedTasks;
                ?>
                المهام الكلية: <?php echo $totalTasks; ?> | 
                المكتملة: <?php echo $completedTasks; ?> | 
                المعلقة: <?php echo $pendingTasks; ?>
            </div>
        </div>
    </div>
</body>
</html>
