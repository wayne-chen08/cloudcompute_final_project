document.getElementById("register-form").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(e.target);
  const messageBox = document.getElementById("message");

  fetch("register.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        messageBox.textContent = "註冊成功，正在前往答題頁...";
        messageBox.className = "message success";
        setTimeout(() => {
          window.location.href = data.redirect;
        }, 1000);
      } else {
        messageBox.textContent = data.message;
        messageBox.className = "message error";
      }
    })
    .catch(err => {
      messageBox.textContent = "註冊失敗，請稍後再試";
      messageBox.className = "message error";
      console.error(err);
    });
});
