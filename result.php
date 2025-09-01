<?php
// result.php
require_once 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if($id <= 0){
    echo "Result id missing.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM eq_results WHERE id = :id LIMIT 1");
$stmt->execute([':id'=>$id]);
$row = $stmt->fetch();

if(!$row){
    echo "Result not found.";
    exit;
}

$score = (int)$row['score'];
$answers = json_decode($row['answers'], true) ?: [];
$created = $row['created_at'] ?? '';

function feedback_for_score($score){
    if($score >= 80) return ['label'=>'High EQ','desc'=>'You show strong emotional awareness, empathy, and regulation. Keep practicing reflection and helping others.'];
    if($score >= 60) return ['label'=>'Above Average EQ','desc'=>'You have solid emotional skills but there are a few areas to polish — try targeted reflection and active listening exercises.'];
    if($score >= 40) return ['label'=>'Moderate EQ','desc'=>'You have basic emotional skills but could benefit from structured practices like journaling, mindfulness, and perspective-taking.'];
    return ['label'=>'Developing EQ','desc'=>'Focus on recognizing emotions, practicing calming techniques, and empathic listening. Small daily exercises help a lot.'];
}

$fb = feedback_for_score($score);

// breakdown by domain (derive from question ids: q1..q12 mapping)
$domain_scores = ['Self-awareness'=>0,'Empathy'=>0,'Regulation'=>0];
$domain_counts = ['Self-awareness'=>0,'Empathy'=>0,'Regulation'=>0];
// We need to map q ids -> domain; recreate same question definitions here or parse id number
// We'll approximate domain by id mapping defined in quiz.php: q1,q2,q7,q11 => Self-awareness; q3,q4,q8,q10 => Empathy; q5,q6,q9,q12 => Regulation
$mapping = [
  'Self-awareness'=> ['q1','q2','q7','q11'],
  'Empathy'=> ['q3','q4','q8','q10'],
  'Regulation'=> ['q5','q6','q9','q12']
];

foreach($mapping as $domain=>$ids){
    foreach($ids as $qid){
        if(isset($answers[$qid])){
            $domain_scores[$domain] += ($answers[$qid]['points'] ?? 0);
            $domain_counts[$domain] += 1;
        }
    }
}

// compute percent per domain
$domain_percent = [];
foreach($domain_scores as $d=>$sum){
    $cnt = $domain_counts[$d] ?: 1;
    $max = $cnt * 3;
    $domain_percent[$d] = round(($sum / $max) * 100);
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>EQ Test Result</title>
<style>
  /* internal CSS for results page */
  :root{--bg:#07102b;--card:#0b254f;--accent:#7c3aed;--muted:#bcd0f8}
  html,body{height:100%;margin:0;font-family:Inter,system-ui,Arial;background:linear-gradient(180deg,var(--bg), #041426);color:#eef6ff}
  .wrap{max-width:920px;margin:28px auto;padding:18px}
  .card{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));padding:20px;border-radius:12px;box-shadow:0 8px 28px rgba(2,6,23,0.6)}
  .score{
    display:flex;align-items:center;gap:20px;justify-content:space-between;
    flex-wrap:wrap;
  }
  .big{font-size:56px;font-weight:800;color:#fff}
  .label{font-size:18px;color:var(--muted)}
  .fb{margin-top:14px;background: linear-gradient(90deg, rgba(124,58,237,0.12), rgba(79,70,229,0.06)); padding:14px;border-radius:10px}
  .domainGrid{display:flex;gap:12px;margin-top:12px;flex-wrap:wrap}
  .bar{height:12px;background:rgba(255,255,255,0.06);border-radius:8px;overflow:hidden}
  .bar > i{height:100%;display:block;background:linear-gradient(90deg,var(--accent),#4f46e5);}
  .controls{margin-top:18px;display:flex;gap:12px}
  .btn{background:linear-gradient(90deg,var(--accent),#4f46e5);color:white;padding:10px 14px;border-radius:10px;border:none;cursor:pointer}
  .btn.ghost{background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted)}
  .share{display:flex;gap:10px;margin-top:8px}
  .meta{color:var(--muted);font-size:13px;margin-top:8px}
</style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="score">
        <div>
          <div class="big"><?php echo htmlspecialchars($score); ?>/100</div>
          <div class="label"><?php echo htmlspecialchars($fb['label']); ?> — <?php echo htmlspecialchars($fb['desc']); ?></div>
        </div>
        <div style="text-align:right">
          <div style="font-size:13px;color:var(--muted)">Completed: <?php echo htmlspecialchars($created); ?></div>
          <div style="margin-top:10px">
            <button class="btn" id="retakeBtn">Retake Test</button>
            <button class="btn ghost" id="downloadBtn">Download Result</button>
          </div>
        </div>
      </div>

      <div class="fb">
        <strong>Personalized Suggestions</strong>
        <ul style="margin:8px 0 0 18px;color:var(--muted)">
          <?php if($score >= 80): ?>
            <li>Keep mentoring others and practice advanced perspective-taking exercises.</li>
            <li>Continue journaling and emotional check-ins.</li>
          <?php elseif($score >= 60): ?>
            <li>Work on mindful reflection: 5 minutes daily journaling.</li>
            <li>Practice active listening with one person per week.</li>
          <?php elseif($score >= 40): ?>
            <li>Try daily breathing or grounding exercises.</li>
            <li>Use "pause-and-reflect" before reacting in stressful moments.</li>
          <?php else: ?>
            <li>Begin with simple recognition: name one emotion you felt today.</li>
            <li>Practice deep breathing and ask clarifying questions in conversations.</li>
          <?php endif; ?>
        </ul>
      </div>

      <div style="margin-top:16px">
        <strong>Breakdown by domain</strong>
        <div class="domainGrid">
          <?php foreach($domain_percent as $d=>$p): ?>
            <div style="flex:1;min-width:200px">
              <div style="display:flex;justify-content:space-between">
                <div style="font-weight:700"><?php echo htmlspecialchars($d); ?></div>
                <div style="color:var(--muted)"><?php echo $p; ?>%</div>
              </div>
              <div class="bar" style="margin-top:8px"><i style="width:<?php echo $p; ?>%"></i></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="controls">
        <div>
          <div class="share">
            <button class="btn" id="shareBtn">Share Result</button>
            <button class="btn ghost" id="copyBtn">Copy Link</button>
          </div>
          <div class="meta">Result saved (ID: <?php echo intval($id); ?>)</div>
        </div>
      </div>
    </div>
  </div>

<script>
  // JS for retake, share, download, copy link — all redirections via JS
  document.getElementById('retakeBtn').addEventListener('click', function(){
    window.location.href = 'quiz.php';
  });

  document.getElementById('copyBtn').addEventListener('click', function(){
    const link = window.location.href;
    navigator.clipboard && navigator.clipboard.writeText(link).then(()=>alert('Link copied to clipboard.'));
  });

  document.getElementById('shareBtn').addEventListener('click', function(){
    const shareText = `I took an EQ test and scored <?php echo $score; ?>/100 — check yours: ${window.location.href}`;
    if(navigator.share){
      navigator.share({title:'My EQ Test Result', text:shareText, url:window.location.href}).catch(()=>alert('Share cancelled'));
    } else {
      prompt('Share this link:', window.location.href);
    }
  });

  document.getElementById('downloadBtn').addEventListener('click', function(){
    // create a small printable page and open in new window to trigger print/save
    const html = `
      <html><head><title>EQ Result</title></head><body>
      <h1>EQ Score: <?php echo $score; ?>/100</h1>
      <p><?php echo htmlspecialchars($fb['label']); ?> — <?php echo htmlspecialchars($fb['desc']); ?></p>
      <p>Generated: <?php echo htmlspecialchars($created); ?></p>
      <pre>Domain breakdown: <?php echo htmlspecialchars(json_encode($domain_percent)); ?></pre>
      </body></html>
    `;
    const w = window.open('', '_blank');
    w.document.write(html);
    w.document.close();
  });
</script>
</body>
</html>
