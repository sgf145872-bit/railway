<?php
/*
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

    include '404.php';
    /*
    // إذا الملف غير موجود
    http_response_code(404);
    echo "<h1>404 - Page Not Found</h1>";
    
}
*/

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

if ($uri == '' || $uri == 'index.php') {
    $file = 'home.php';
} else {
    $file = $uri . '.php';
}

if (file_exists($file)) {
    include $file;
} else {
    include '404.php';
}

?>
