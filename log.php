<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

define('TURSO_URL', 'https://ai-code-guard-tovuongduckhai-stack.aws-ap-northeast-1.turso.io');
define('TURSO_TOKEN', 'eyJhbGciOiJFZERTQSIsInR5cCI6IkpXVCJ9.eyJhIjoicnciLCJpYXQiOjE3ODMyMzIxODcsImlkIjoiMDE5ZjMwZTgtM2MwMS03OWE2LTk2ZjUtY2Q1ZWJkZjA0N2Q5Iiwia2lkIjoiWFNJbWRySGJxN3JvTzhHYm9rMFNhR1FmaHB0WTlyalgxdnJ4RjVYZTNWayIsInJpZCI6IjBjMTY0YWJiLWQxNTItNDAyNy05NmNmLTRmYzAwNTljOTA5MyJ9.KN9SHrvvM5qkww4Ul37nJ93xGwPpTZWQ6kD47-EVOdqSSq3b3TohkXIorDKGtSAb3cWBEtpKQS1OI5nD4EckAg');

function turso_query($sql, $args = []) {
    $ch = curl_init(TURSO_URL . '/v2/pipeline');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . TURSO_TOKEN
        ],
        CURLOPT_POSTFIELDS => json_encode([
            'requests' => [
                ['type' => 'execute', 'stmt' => ['sql' => $sql, 'args' => $args]],
                ['type' => 'close']
            ]
        ])
    ]);
    $result = curl_exec($ch);
    $err = curl_error($ch);
    curl_close($ch);
    if ($err) {
        error_log("Turso curl error: $err");
    }
    return json_decode($result, true);
}

$create = turso_query("CREATE TABLE IF NOT EXISTS events (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    event TEXT,
    payload TEXT,
    ip TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['event'])) {
    echo json_encode(['status' => 'error', 'msg' => 'Invalid input']);
    exit;
}

$insert = turso_query("INSERT INTO events (event, payload, ip) VALUES (?, ?, ?)", [
    ['type' => 'text', 'value' => $input['event']],
    ['type' => 'text', 'value' => json_encode($input['payload'] ?? [])],
    ['type' => 'text', 'value' => $_SERVER['REMOTE_ADDR'] ?? 'unknown']
]);

// Debug: trả luôn kết quả Turso về để biết fail ở đâu nếu còn lỗi
echo json_encode(['status' => 'ok', 'create_result' => $create, 'insert_result' => $insert]);
