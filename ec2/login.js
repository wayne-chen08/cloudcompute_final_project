// 綁定「登入表單」的送出事件
document.getElementById("login-form").addEventListener("submit", function (e) {
    e.preventDefault(); // 阻止表單原本的提交刷新行為

    const form = e.target; // 抓到目前這個表單元素
    const formData = new FormData(form); // 把表單資料打包成FormData，方便送到後端

    // 使用fetch發送POST請求到login.php
    fetch("login.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json()) // 伺服器回傳的是JSON格式
    .then(data => {
        if (data.success) {
            // 如果登入成功，根據伺服器給的redirect URL導向新頁面
            window.location.href = data.redirect;
        } 
        else {
            // 如果登入失敗，跳出錯誤提示訊息
            alert(data.message);
        }
    })
    .catch(err => {
        // 如果網路或伺服器錯誤，跳出通用錯誤提示
        alert("登入失敗，請稍後再試");
        console.error(err);
    });
});

// 切換密碼欄位顯示/隱藏的功能
function togglePassword() {
    const passwordInput = document.querySelector('.pas'); // 找到密碼輸入框
    const icon = document.querySelector('.show-hide');    // 找到眼睛圖示

    if (passwordInput.type === 'password') {
        // 如果目前是密碼模式（隱藏起來的），改成普通文字（顯示密碼）
        passwordInput.type = 'text';
        icon.setAttribute('name', 'eye-off-outline'); // 設定眼睛關閉的圖案
    } 
    else {
        // 如果目前是文字模式，改回密碼模式（隱藏密碼）
        passwordInput.type = 'password';
        icon.setAttribute('name', 'eye-outline'); // 設定正常眼睛圖案
    }
}
