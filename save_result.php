<?php
// save_result.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error'=>'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['score']) || !isset($input['answers'])) {
    http_response_code(400);
    echo json_encode(['error'=>'Invalid payload']);
    exit;
}

$score = (int)$input['score'];
$answers_json = json_encode($input['answers'], JSON_UNESCAPED_UNICODE);

require_once 'db.php'; // creates $pdo

try {
    $stmt = $pdo->prepare("INSERT INTO eq_results (score, answers) VALUES (:score, :answers)");
    $stmt->execute([':score'=>$score, ':answers'=>$answers_json]);
    $id = $pdo->lastInsertId();
    echo json_encode(['success'=>true, 'id'=>$id]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error'=>'DB error: ' . $e->getMessage()]);
}
