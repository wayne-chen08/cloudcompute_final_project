// 載入當日題目
fetch('get_today_question.php')
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      // 題目文字
      document.getElementById("question-text").textContent = data.description;

      // 題目圖片（若有）
      const img = document.getElementById("question-image");
      if (data.pic) {
        img.src = data.pic;
        img.style.display = "block";
      } else {
        img.style.display = "none";
      }

      // 記下題目 ID，提交答案要用
      window.CURRENT_QUESTION_ID = data.question_id;
    } else {
      alert(data.message || "題目載入失敗");
    }
  })
  .catch(err => {
    alert("無法載入題目，請稍後再試");
    console.error(err);
  });

// 顯示右上角作答狀態（Solved / Attempted / Unsolved）
fetch("get_answer_status.php")
  .then(res => res.json())
  .then(data => {
    const box = document.getElementById("status-box");

    if (data.status === "correct") {
      box.innerHTML = 'Solved <span class="icon-correct">&#10003;</span>';
      document.getElementById("show-explanation").disabled = false;
    } else if (data.status === "wrong") {
      box.innerHTML = 'Attempted <span class="icon-wrong">&#10005;</span>';
      document.getElementById("show-explanation").disabled = false;
    } else {
      box.innerHTML = 'Unsolved <span class="icon-unknown">?</span>';
    }
  })
  .catch(err => {
    console.error("作答狀態載入失敗", err);
  });

// 提交答案事件
document.getElementById("question-form").addEventListener("submit", function (e) {
  e.preventDefault();
  const answer = document.getElementById("user_answer").value;
  const qid = window.CURRENT_QUESTION_ID;

  if (!qid) {
    alert("題目尚未載入，請稍後再試");
    return;
  }

  fetch("answer.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ answer, question_id: qid })
  })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      if (data.success) {
        // 重新載入狀態與解析按鈕狀態
        location.reload(); // 也可以選擇只更新狀態區塊與解析按鈕
      }
    })
    .catch(err => {
      alert("提交失敗，請稍後再試");
      console.error(err);
    });
});

// 查看解析按鈕
document.getElementById("show-explanation").addEventListener("click", function () {
  fetch("get_explanation.php")
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const box = document.getElementById("explanation-box");
        box.textContent = data.explanation;
        box.style.display = "block";
      } else {
        alert(data.message);
      }
    })
    .catch(err => {
      alert("無法取得解析，請稍後再試");
      console.error(err);
    });
});
