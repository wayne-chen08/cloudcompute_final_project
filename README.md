# final
cloudcompute_final_project

IAM沒有辦法用，所以沒辦法用程式加圖片到s3

EC2:
登入畫面 + 回答問題畫面（題目來自當日題目DB）
RDS:
用戶（ID, 帳號密碼、答題次數、正確次數、正確率）+ 題庫（題目ID、題目敘述、答案）+ 當日題目（日期、題目ID）+ 答題記錄 (用戶ID、題目ID、日期、是否正確)
lambda:
定期推送從題庫中抽取1題推送到當日題目(都是DB)
S3:
存要用的素材

要改的地方：
lambda_function：裡面的db_settings要改成rds
dblink.php：改資料庫的連線（應該只需要改這個，如果有問題可以檢查看看其他的php檔，可能我有忘記改的）
rds/questionbase.sql：pic的裡面的連結也要改自己存的s3位址
index.html：cube放的連結（s3）
