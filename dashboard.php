<?php
$db = new PDO('sqlite:' . __DIR__ . '/data.db');
$db->exec("CREATE TABLE IF NOT EXISTS events (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    event TEXT,
    payload TEXT,
    ip TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$total = $db->query("SELECT COUNT(*) FROM events")->fetchColumn();

$byEvent = $db->query("
    SELECT event, COUNT(*) as count 
    FROM events 
    GROUP BY event 
    ORDER BY count DESC
")->fetchAll(PDO::FETCH_ASSOC);

$byPlan = $db->query("
    SELECT payload, COUNT(*) as count 
    FROM events 
    WHERE event = 'purchase_click'
    GROUP BY payload
    ORDER BY count DESC
")->fetchAll(PDO::FETCH_ASSOC);

$byTool = $db->query("
    SELECT payload, COUNT(*) as count 
    FROM events 
    WHERE event = 'click_tool'
    GROUP BY payload
    ORDER BY count DESC
")->fetchAll(PDO::FETCH_ASSOC);

$recent = $db->query("
    SELECT event, payload, ip, created_at 
    FROM events 
    ORDER BY created_at DESC 
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quality Guard - Validation Dashboard</title>
  <style>
    body { font-family: -apple-system, BlinkMacSystemFont, Arial, sans-serif; margin: 40px; background: #f8f9fa; color: #202124; }
    h1 { text-align: center; color: #1a73e8; }
    .cards { display: flex; gap: 20px; justify-content: center; margin: 30px 0; }
    .card { background: white; border: 1px solid #dadce0; padding: 20px; text-align: center; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border-radius: 8px; min-width: 150px; }
    .card h2 { font-size: 40px; margin: 0; color: #1a73e8; }
    .card p { margin: 5px 0 0; font-size: 14px; color: #5f6368; font-weight: bold; }
    table { width: 85%; margin: 20px auto; border-collapse: collapse; background: white; border: 1px solid #dadce0; border-radius: 8px; overflow: hidden; }
    th, td { padding: 12px; text-align: center; border-bottom: 1px solid #dadce0; }
    th { background: #f1f3f4; color: #202124; font-weight: bold; }
    tr:hover { background: #f8f9fa; }
    h2.section { text-align: center; margin-top: 40px; color: #3c4043; font-size: 22px; }
    .refresh { display: block; text-align: center; margin: 20px; color: #1a73e8; cursor: pointer; text-decoration: none; font-weight: bold; }
  </style>
</head>
<body>

<h1>📊 Quality Guard - Analytics Control Panel</h1>
<a class="refresh" onclick="location.reload()">🔄 Refresh Metrics</a>

<div class="cards">
  <div class="card">
    <h2><?= $total ?></h2>
    <p>Total Metrics Logged</p>
  </div>
  <?php
  $purchases = 0; $clicks = 0;
  foreach ($byEvent as $e) {
    if ($e['event'] === 'purchase_click') $purchases = $e['count'];
    if ($e['event'] === 'click_tool') $clicks = $e['count'];
  }
  ?>
  <div class="card">
    <h2><?= $purchases ?></h2>
    <p>Premium Plan Interacts</p>
  </div>
  <div class="card">
    <h2><?= $clicks ?></h2>
    <p>Core Invisible AI Clicks</p>
  </div>
</div>

<h2 class="section">💰 Monetization Intent (Buy Now by Plan)</h2>
<table>
  <tr><th>Product Version</th><th>Selected Tier</th><th>Total Conversion Clicks</th></tr>
  <?php foreach ($byPlan as $row):
    $payload = json_decode($row['payload'], true);
    $plan = $payload['plan'] ?? $row['payload'];
    $product = $payload['product'] ?? 'N/A';
  ?>
  <tr>
    <td><strong><?= htmlspecialchars($product) ?></strong></td>
    <td><span style="color: #1a73e8; font-weight: bold;"><?= strtoupper(htmlspecialchars($plan)) ?></span></td>
    <td><?= $row['count'] ?></td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($byPlan)): ?>
  <tr><td colspan="3">No interest logged yet</td></tr>
  <?php endif; ?>
</table>

<h2 class="section">🔗 Interactive Feature Simulation Logs</h2>
<table>
  <tr><th>Product Version</th><th>Triggered Action</th><th>Total Interacts</th></tr>
  <?php foreach ($byTool as $row):
    $payload = json_decode($row['payload'], true);
    $tool = $payload['tool'] ?? $row['payload'];
    $product = $payload['product'] ?? 'N/A';
  ?>
  <tr>
    <td><strong><?= htmlspecialchars($product) ?></strong></td>
    <td><code style="background: #f1f3f4; padding: 4px; border-radius: 4px;"><?= htmlspecialchars($tool) ?></code></td>
    <td><?= $row['count'] ?></td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($byTool)): ?>
  <tr><td colspan="3">No core actions triggered yet</td></tr>
  <?php endif; ?>
</table>

<h2 class="section">🕐 Real-time Event Stream (Last 10 Logs)</h2>
<table>
  <tr><th>Event Type</th><th>Payload Metadata</th><th>Remote IP</th><th>Timestamp</th></tr>
  <?php foreach ($recent as $row): ?>
  <tr>
    <td><span style="background: #e8f0fe; color: #1a73e8; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;"><?= htmlspecialchars($row['event']) ?></span></td>
    <td style="text-align: left; font-family: monospace; font-size: 12px;"><?= htmlspecialchars($row['payload']) ?></td>
    <td><?= htmlspecialchars($row['ip']) ?></td>
    <td><?= $row['created_at'] ?></td>
  </tr>
  <?php endforeach; ?>
  <?php if (empty($recent)): ?>
  <tr><td colspan="4">Waiting for incoming traffic data...</td></tr>
  <?php endif; ?>
</table>

</body>
</html>