<?php
// index.php
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Emotional Intelligence (EQ) Test</title>
<style>
  /* ===== internal CSS (beautiful responsive design) ===== */
  :root{
    --bg1:#0f172a;
    --bg2:#0b3d91;
    --card:#ffffff;
    --accent:#7c3aed;
    --glass: rgba(255,255,255,0.06);
    --muted:#cbd5e1;
  }
  html,body{height:100%;margin:0;font-family:Inter,ui-sans-serif,system-ui,Segoe UI,Roboto,"Helvetica Neue",Arial;}
  body{
    background: linear-gradient(135deg,var(--bg1), #0b254f 50%, #07102b);
    color: #e6eef8;
    display:flex;align-items:center;justify-content:center;padding:24px;
  }
  .container{max-width:980px;width:100%;}
  .hero{
    background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
    border-radius:16px;padding:28px;box-shadow: 0 10px 30px rgba(2,6,23,0.6);
    display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:center;
    backdrop-filter: blur(6px);
  }
  .left h1{font-size:32px;margin:0 0 10px 0;color:#fff;letter-spacing:-0.3px;}
  .left p{color:var(--muted);line-height:1.6;margin:0 0 18px;}
  .features{display:flex;gap:12px;flex-wrap:wrap}
  .chip{background:linear-gradient(90deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));padding:10px 12px;border-radius:10px;color:var(--muted);font-size:13px}
  .cta{display:flex;gap:12px;margin-top:16px}
  .btn{
    background: linear-gradient(90deg,var(--accent), #4f46e5);
    color:white;padding:12px 16px;border-radius:12px;font-weight:600;border:none;cursor:pointer;
    box-shadow: 0 6px 18px rgba(79,70,229,0.28);
  }
  .btn.secondary{background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted)}
  .card{
    background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));
    border-radius:12px;padding:18px;color:var(--muted);
  }
  .stat{font-size:22px;color:#fff;font-weight:700}
  footer{margin-top:16px;color:rgba(230,238,248,0.6);font-size:13px;text-align:center}
  /* responsive */
  @media (max-width:900px){
    .hero{grid-template-columns:1fr; padding:20px}
    .left{order:1}
  }
</style>
</head>
<body>
  <div class="container">
    <div class="hero">
      <div class="left">
        <h1>Emotional Intelligence (EQ) Test</h1>
        <p>Measure your emotional awareness, empathy, and regulation. This short, reliable test provides a score and personalized feedback so you can discover strengths and areas to grow.</p>
        <div class="features">
          <div class="chip">~ 12 questions</div>
          <div class="chip">10–15 minutes</div>
          <div class="chip">Personalized feedback</div>
          <div class="chip">Mobile friendly</div>
        </div>

        <div class="cta">
          <button class="btn" id="startBtn">Start Test</button>
          <button class="btn secondary" id="learnBtn">How it works</button>
        </div>

        <div style="margin-top:12px;color:var(--muted);font-size:14px">
          <strong>Tip:</strong> Answer honestly for best insight. Your responses are stored to the database you configured.
        </div>
      </div>

      <div class="card" style="text-align:center">
        <div style="font-size:13px;color:var(--muted)">Your emotional fitness snapshot</div>
        <div style="display:flex;align-items:center;justify-content:center;gap:20px;margin-top:14px">
          <div>
            <div class="stat">Self-awareness</div>
            <div style="color:var(--muted);margin-top:6px">Notice emotions & triggers</div>
          </div>
          <div>
            <div class="stat">Empathy</div>
            <div style="color:var(--muted);margin-top:6px">Understand others</div>
          </div>
        </div>
        <div style="margin-top:18px">
          <button class="btn" id="viewSample">Preview Questions</button>
        </div>
      </div>
    </div>

    <footer>
      Built with ❤️ — an interactive EQ test. Results saved to your DB: <code>dbmvurzslbtuph</code>
    </footer>
  </div>

<script>
  // All navigation must be via JS per your instruction
  document.getElementById('startBtn').addEventListener('click', function(){
    // simple redirection
    window.location.href = 'quiz.php';
  });
  document.getElementById('learnBtn').addEventListener('click', function(){
    alert('This test measures: self-awareness, empathy, and emotion regulation. Answer honestly.'); 
  });
  document.getElementById('viewSample').addEventListener('click', function(){
    // redirect to quiz with sample view param
    window.location.href = 'quiz.php?preview=1';
  });
</script>
</body>
</html>
