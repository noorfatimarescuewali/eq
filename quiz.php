<?php
// quiz.php
// Questions defined here (server-side) — can later be moved into DB if you want.
$questions = [
    // Each question has text, options (text + points)
    // points: 0..3 (higher = more emotionally intelligent)
    [
        'id'=>'q1',
        'domain'=>'Self-awareness',
        'text'=>'I can easily identify what emotion I am feeling at the moment.',
        'options'=>[
            ['text'=>'Strongly disagree','points'=>0],
            ['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],
            ['text'=>'Strongly agree','points'=>3],
        ]
    ],
    [
        'id'=>'q2',
        'domain'=>'Self-awareness',
        'text'=>'I can say what usually triggers my strong emotions.',
        'options'=>[
            ['text'=>'Strongly disagree','points'=>0],
            ['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],
            ['text'=>'Strongly agree','points'=>3],
        ]
    ],
    [
        'id'=>'q3',
        'domain'=>'Empathy',
        'text'=>'I find it easy to understand how others feel in social situations.',
        'options'=>[
            ['text'=>'Strongly disagree','points'=>0],
            ['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],
            ['text'=>'Strongly agree','points'=>3],
        ]
    ],
    [
        'id'=>'q4',
        'domain'=>'Empathy',
        'text'=>'When someone is upset, I can usually tell if they need support or space.',
        'options'=>[
            ['text'=>'Strongly disagree','points'=>0],
            ['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],
            ['text'=>'Strongly agree','points'=>3],
        ]
    ],
    [
        'id'=>'q5',
        'domain'=>'Regulation',
        'text'=>'When I am stressed, I use strategies to calm myself down effectively.',
        'options'=>[
            ['text'=>'Strongly disagree','points'=>0],
            ['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],
            ['text'=>'Strongly agree','points'=>3],
        ]
    ],
    [
        'id'=>'q6',
        'domain'=>'Regulation',
        'text'=>'I can manage my reactions so they don’t harm important relationships.',
        'options'=>[
            ['text'=>'Strongly disagree','points'=>0],
            ['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],
            ['text'=>'Strongly agree','points'=>3],
        ]
    ],
    // add a few more questions to reach ~12 (repeat pattern)
    [
        'id'=>'q7','domain'=>'Self-awareness','text'=>'I reflect on my feelings to learn about myself.','options'=>[
            ['text'=>'Strongly disagree','points'=>0],['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],['text'=>'Strongly agree','points'=>3]
        ]
    ],
    [
        'id'=>'q8','domain'=>'Empathy','text'=>'I consider other people’s perspectives before judging them.','options'=>[
            ['text'=>'Strongly disagree','points'=>0],['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],['text'=>'Strongly agree','points'=>3]
        ]
    ],
    [
        'id'=>'q9','domain'=>'Regulation','text'=>'I can refocus quickly after a setback.','options'=>[
            ['text'=>'Strongly disagree','points'=>0],['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],['text'=>'Strongly agree','points'=>3]
        ]
    ],
    [
        'id'=>'q10','domain'=>'Empathy','text'=>'I notice nonverbal cues (tone, facial expression) in conversations.','options'=>[
            ['text'=>'Strongly disagree','points'=>0],['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],['text'=>'Strongly agree','points'=>3]
        ]
    ],
    [
        'id'=>'q11','domain'=>'Self-awareness','text'=>'I can name my strengths and weaknesses clearly.','options'=>[
            ['text'=>'Strongly disagree','points'=>0],['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],['text'=>'Strongly agree','points'=>3]
        ]
    ],
    [
        'id'=>'q12','domain'=>'Regulation','text'=>'I avoid making hasty decisions when emotional.','options'=>[
            ['text'=>'Strongly disagree','points'=>0],['text'=>'Somewhat disagree','points'=>1],
            ['text'=>'Somewhat agree','points'=>2],['text'=>'Strongly agree','points'=>3]
        ]
    ],
];

// expose to JS
$questions_json = json_encode($questions, JSON_UNESCAPED_UNICODE);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Take the EQ Test</title>
<style>
  /* ===== internal CSS (pretty quiz UI) ===== */
  :root{--bg:#07102b;--panel:#0b254f;--accent:#7c3aed;--muted:#bcd0f8}
  html,body{height:100%;margin:0;font-family:Inter,system-ui,Arial;color:#eef6ff;background:linear-gradient(180deg,var(--bg), #05203a);}
  .wrap{max-width:920px;margin:28px auto;padding:18px;}
  .card{background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0.01));padding:20px;border-radius:12px;box-shadow:0 8px 28px rgba(2,6,23,0.6)}
  header{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
  h2{margin:0;color:#fff}
  .progress{height:10px;background:rgba(255,255,255,0.04);border-radius:10px;overflow:hidden}
  .progress > div{height:100%;width:0;background:linear-gradient(90deg,var(--accent),#4f46e5);transition:width 300ms ease}
  .question{margin-top:18px}
  .qtext{font-weight:600;color:#f8fbff;margin-bottom:12px}
  .options{display:grid;gap:10px}
  .option{
    background:linear-gradient(180deg, rgba(255,255,255,0.01), rgba(255,255,255,0.00));
    border-radius:10px;padding:12px;border:1px solid rgba(255,255,255,0.04);cursor:pointer;
  }
  .option.selected{border-color:rgba(124,58,237,0.9);box-shadow:0 8px 22px rgba(124,58,237,0.12);transform:translateY(-2px)}
  .nav{display:flex;justify-content:space-between;margin-top:18px;gap:12px}
  .btn{background:linear-gradient(90deg,var(--accent),#4f46e5);color:white;padding:10px 14px;border-radius:10px;border:none;cursor:pointer}
  .btn.ghost{background:transparent;border:1px solid rgba(255,255,255,0.06);color:var(--muted)}
  .meta{font-size:13px;color:var(--muted)}
  @media (max-width:700px){
    .nav{flex-direction:column}
    header{flex-direction:column;align-items:flex-start;gap:8px}
  }
</style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <header>
        <div>
          <h2>Emotional Intelligence Test</h2>
          <div class="meta">Answer honestly • 12 questions • No external JS/CSS</div>
        </div>
        <div style="min-width:260px">
          <div class="progress" aria-hidden="true"><div id="progFill"></div></div>
          <div style="text-align:right;margin-top:6px;color:rgba(230,238,248,0.7)" id="progressText">Question 1 of 12</div>
        </div>
      </header>

      <main id="quizArea">
        <!-- dynamic content -->
      </main>

      <div class="nav">
        <button class="btn ghost" id="prevBtn" disabled>&larr; Previous</button>
        <div style="display:flex;gap:10px">
          <button class="btn ghost" id="saveDraftBtn">Save Draft</button>
          <button class="btn" id="nextBtn">Next &rarr;</button>
        </div>
      </div>
    </div>
  </div>

<script>
  // ===== internal JS for quiz behavior =====
  const questions = <?php echo $questions_json; ?>;
  let current = 0;
  const answers = {}; // store selected option index
  const total = questions.length;

  const quizArea = document.getElementById('quizArea');
  const prevBtn = document.getElementById('prevBtn');
  const nextBtn = document.getElementById('nextBtn');
  const saveDraftBtn = document.getElementById('saveDraftBtn');
  const progFill = document.getElementById('progFill');
  const progressText = document.getElementById('progressText');

  function renderQuestion(index){
    const q = questions[index];
    quizArea.innerHTML = `
      <div class="question">
        <div class="qtext">${index+1}. ${escapeHtml(q.text)}</div>
        <div class="options" id="optionsArea">
          ${q.options.map((opt, i)=>`<div class="option" data-i="${i}" data-points="${opt.points}">${escapeHtml(opt.text)}</div>`).join('')}
        </div>
        <div style="margin-top:12px;color:var(--muted);font-size:13px"><strong>Domain:</strong> ${escapeHtml(q.domain)}</div>
      </div>
    `;
    // highlight if selected
    const optionsArea = document.getElementById('optionsArea');
    optionsArea.querySelectorAll('.option').forEach(el=>{
      el.addEventListener('click', function(){
        // clear previous
        optionsArea.querySelectorAll('.option').forEach(x=>x.classList.remove('selected'));
        this.classList.add('selected');
        answers[q.id] = parseInt(this.getAttribute('data-i'));
      });
    });
    // preselect if answer exists
    if(typeof answers[q.id] !== 'undefined'){
      const sel = optionsArea.querySelector(`.option[data-i="${answers[q.id]}"]`);
      if(sel) sel.classList.add('selected');
    }
    // update UI
    prevBtn.disabled = (index === 0);
    nextBtn.textContent = (index === total-1) ? 'Finish & Submit' : 'Next →';
    updateProgress(index);
  }

  function updateProgress(idx){
    const pct = Math.round(((idx+1)/total)*100);
    progFill.style.width = pct + '%';
    progressText.textContent = `Question ${idx+1} of ${total}`;
  }

  prevBtn.addEventListener('click', ()=>{ if(current>0){ current--; renderQuestion(current);} });
  nextBtn.addEventListener('click', ()=>{
    const q = questions[current];
    if(typeof answers[q.id] === 'undefined'){
      if(!confirm('You did not select an option for this question. Continue anyway?')) return;
    }
    if(current < total-1){
      current++;
      renderQuestion(current);
      window.scrollTo({top:0,behavior:'smooth'});
    } else {
      // last question -> compute score, save via fetch to save_result.php, then redirect with JS
      const computed = computeScore();
      // send data to server
      nextBtn.disabled = true;
      nextBtn.textContent = 'Submitting...';
      fetch('save_result.php', {
        method: 'POST',
        headers:{'Content-Type':'application/json'},
        body: JSON.stringify({score:computed.score, answers:computed.answers})
      }).then(r=>r.json()).then(data=>{
        if(data && data.id){
          // redirect to result page using JS (per instruction)
          window.location.href = 'result.php?id=' + encodeURIComponent(data.id);
        } else {
          alert('There was an error saving your result. Try again.');
          nextBtn.disabled = false;
          nextBtn.textContent = 'Finish & Submit';
        }
      }).catch(err=>{
        console.error(err);
        alert('Save failed: ' + err);
        nextBtn.disabled = false;
        nextBtn.textContent = 'Finish & Submit';
      });
    }
  });

  saveDraftBtn.addEventListener('click', ()=>{
    // store to localStorage (quick draft)
    localStorage.setItem('eq_draft', JSON.stringify(answers));
    alert('Draft saved locally in your browser.');
  });

  function computeScore(){
    // sum option points and normalize 0-100
    let sum = 0;
    let max = total * 3; // each question max 3 points
    const answerDetails = {};
    questions.forEach(q=>{
      const idx = answers[q.id];
      const pts = (typeof idx === 'number') ? (q.options[idx].points || 0) : 0;
      sum += pts;
      answerDetails[q.id] = {selectedIndex: idx ?? null, points: pts};
    });
    const scorePercent = Math.round((sum / max) * 100);
    return {score:scorePercent, answers:answerDetails};
  }

  function escapeHtml(str){ return String(str).replace(/[&<>"']/g, function(m){ return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]; }); }

  // load draft if present
  (function init(){
    const draft = localStorage.getItem('eq_draft');
    if(draft){
      try{
        const obj = JSON.parse(draft);
        Object.assign(answers, obj);
      }catch(e){}
    }
    renderQuestion(current);
    // preview mode check: if ?preview=1, show a quick modal sample (optional)
    const params = new URLSearchParams(window.location.search);
    if(params.get('preview') === '1'){
      alert('Preview mode: this opens the quiz in preview. Start -> full test.');
    }
  })();
</script>
</body>
</html>
