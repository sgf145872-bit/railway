<?php
// قراءة المسار المطلوب
$path = isset($_GET['path']) ? trim($_GET['path'], '/') : '';

// إذا لم يتم تحديد مسار، افترض الصفحة الرئيسية
if ($path == '') {
    $file = 'home.php';
} else {
    $file = $path . '.php';
}

// التحقق من وجود الملف المطلوب
if (file_exists($file)) {
    // تمرير متغيرات GET كما هي (تلقائيًا)
    include $file;
} else {

    include .'404.php';
    /*
    // إذا الملف غير موجود
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    */
}
?>
