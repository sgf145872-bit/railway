<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 تطبيق إدارة المهام</h1>
            <p>نظم مهامك اليومية بسهولة</p>
            <nav class="nav">
                <a href="/" class="nav-link active">الرئيسية</a>
                <a href="/about" class="nav-link">عن التطبيق</a>
                <a href="/info" class="nav-link">معلومات</a>
                <a href="/contact" class="nav-link">اتصل بنا</a>
            </nav>
        </div>
        
        <div class="content">
            <form method="POST" action="/post-task" class="add-task-form">
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
                                <a href="/toggle-task?id=<?php echo $task['id']; ?>" class="btn toggle-btn">
                                    <?php echo $task['completed'] ? 'إلغاء' : 'إكمال'; ?>
                                </a>
                                <a href="/delete-task?id=<?php echo $task['id']; ?>" class="btn delete-btn" 
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
