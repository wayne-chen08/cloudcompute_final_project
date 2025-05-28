// 載入當日題目
fetch('get_today_question.php')
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      document.getElementById("question-text").textContent = data.description;
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

  fetch("answer.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ answer })
  })
    .then(res => res.json())
    .then(data => {
      alert(data.message);
      if (data.success) {
        // 重新載入作答狀態與解析按鈕
        location.reload(); // 可改成局部刷新
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
