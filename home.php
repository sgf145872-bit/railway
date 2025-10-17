<?php
// طريقة 2: استخدام كائن
class Response {
    public $message = "serveron";
}

$response = new Response();
echo json_encode($response);

// طريقة 3: مع إضافة رأس JSON
header('Content-Type: application/json');
echo json_encode(["status" => "serveron"]);

// طريقة 4: مع خيارات إضافية
echo json_encode(["response" => "serveron"], JSON_PRETTY_PRINT);
?>
