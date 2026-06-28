<?php
$name  = isset($_GET['name'])  ? trim($_GET['name'])  : 'AI Code Guard';
$desc  = isset($_GET['desc'])  ? trim($_GET['desc'])  : 'Fix hidden logic bugs and edge cases instantly';
$basic = isset($_GET['basic']) ? trim($_GET['basic']) : '19';
$pro   = isset($_GET['pro'])   ? trim($_GET['pro'])   : '49';
$ultra = isset($_GET['ultra']) ? trim($_GET['ultra']) : '99';

$slug = strtolower(str_replace(' ', '-', $name));
$dir = "products/$slug";
if (!file_exists($dir)) {
    mkdir($dir, 0755, true);
}

$html = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>$name - AI-Generated Code Quality Guard</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
  <div style="max-width: 800px; margin: 40px auto; padding: 20px; font-family: Arial, sans-serif;">
    <span style="background: #e6f4ea; color: #137333; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 14px;">⚡ No-Bloatware MVP</span>
    <h1 style="font-size: 36px; margin-top: 10px; color: #1a1a1a;">$name</h1>
    <p style="font-size: 18px; color: #5f6368; line-height: 1.6;">$desc</p>

    <div style="background: #fff; border: 1px solid #dadce0; padding: 25px; border-radius: 8px; margin: 30px 0; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
      <h3 style="margin-top: 0; color: #1a73e8;">⚙️ Core Feature: Invisible AI Analysis</h3>
      <p style="color: #5f6368; font-size: 14px;">Paste your AI-generated code here. Our system simulated under a strict QA/Hacker persona to detect 5 fatal edge cases (Memory leaks, Webhook drops, Null pointer errors).</p>
      
      <textarea placeholder="Paste your AI-generated Python/JS code here..." style="width: 100%; height: 120px; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-family: monospace; box-sizing: border-box;"></textarea>
      
      <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center;">
        <select style="padding: 8px; border-radius: 4px; border: 1px solid #ccc;">
          <option>Python</option>
          <option>JavaScript / TypeScript</option>
          <option>PHP / Go</option>
        </select>
        <button onclick="simulateToolClick('simulate_edge_cases_btn')" style="background: #1a73e8; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight: bold;">Check Code & Fix Instantly</button>
      </div>
    </div>

    <h2 style="text-align: center; margin-top: 40px;">Pricing Plans</h2>
    <div style="display: flex; gap: 20px; margin-top: 20px;">
      <div style="flex: 1; border: 1px solid #dadce0; padding: 20px; border-radius: 8px; text-align: center; background: white;">
        <h3>Basic</h3>
        <p style="font-size: 24px; font-weight: bold; color: #1a73e8;">\$$basic <span style="font-size: 14px; font-weight: normal; color: #5f6368;">/ month</span></p>
        <p style="font-size: 13px; color: #5f6368;">Manual Code Scan<br>5 Edge Case Scenarios</p>
        <button onclick="showPopup('basic')" style="width: 100%; background: #333; color: white; border: none; padding: 8px; border-radius: 4px; cursor: pointer; margin-top: 15px;">Get Started</button>
      </div>
      <div style="flex: 1; border: 2px solid #1a73e8; padding: 20px; border-radius: 8px; text-align: center; background: white; position: relative;">
        <span style="position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: #1a73e8; color: white; padding: 2px 10px; border-radius: 10px; font-size: 11px; font-weight: bold;">POPULAR</span>
        <h3>Pro</h3>
        <p style="font-size: 24px; font-weight: bold; color: #1a73e8;">\$$pro <span style="font-size: 14px; font-weight: normal; color: #5f6368;">/ month</span></p>
        <p style="font-size: 13px; color: #5f6368;">GitHub Webhook Sync<br>Auto Unit Test & Bug Fix</p>
        <button onclick="showPopup('pro')" style="width: 100%; background: #1a73e8; color: white; border: none; padding: 8px; border-radius: 4px; cursor: pointer; margin-top: 15px;">Upgrade Pro</button>
      </div>
      <div style="flex: 1; border: 1px solid #dadce0; padding: 20px; border-radius: 8px; text-align: center; background: white;">
        <h3>Ultra</h3>
        <p style="font-size: 24px; font-weight: bold; color: #1a73e8;">\$$ultra <span style="font-size: 14px; font-weight: normal; color: #5f6368;">/ month</span></p>
        <p style="font-size: 13px; color: #5f6368;">Unlimited Repositories<br>Auto-Retry Resilience Network</p>
        <button onclick="showPopup('ultra')" style="width: 100%; background: #333; color: white; border: none; padding: 8px; border-radius: 4px; cursor: pointer; margin-top: 15px;">Go Enterprise</button>
      </div>
    </div>
  </div>

  <div id="popup" style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;">
    <div style="background: white; width: 400px; margin: 15% auto; padding: 20px; border-radius: 8px; text-align: center; position: relative;">
      <span onclick="closePopup()" style="position: absolute; right: 15px; top: 10px; cursor: pointer; font-size: 20px; font-weight: bold;">&times;</span>
      <h2 style="color: #137333;">🎯 Market Interest Logged</h2>
      <p style="color: #5f6368; font-size: 14px;">Thank you for your interest! This feature is part of our upcoming validation launch for the AI-Generated Code Quality Guard system.</p>
    </div>
  </div>

  <script>
    async function sendEvent(eventName, payload) {
      await fetch('/log.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ event: eventName, payload, product: '$slug' })
      });
    }
    function showPopup(plan) {
      document.getElementById("popup").style.display = "block";
      sendEvent('purchase_click', { plan });
    }
    function simulateToolClick(toolName) {
      alert("Simulating Edge Case Analysis workflow... Action logged!");
      sendEvent('click_tool', { tool: toolName });
    }
    function closePopup() {
      document.getElementById("popup").style.display = "none";
    }
  </script>
</body>
</html>
HTML;

file_put_contents("$dir/index.html", $html);
echo "\n✅ Tạo xong Landing Page: $dir/index.html\n";
echo "🌐 URL sau khi deploy: /products/$slug/\n";
?>
