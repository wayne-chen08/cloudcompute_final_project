document.getElementById("question-form").addEventListener("submit", function(e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("admin.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.text())
  .then(result => {
    document.getElementById("result").innerText = result;
  })
  .catch(err => {
    document.getElementById("result").innerText = "錯誤：" + err;
  });
});
