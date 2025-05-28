import pymysql
import random
import datetime

def lambda_handler(event, context):
    # 資料庫連線設定
    db_settings = {
        "host": "localhost",  
        "port": 3308,
        "user": "admin",
        "password": "1234",
        "db": "daily_question",
        "charset": "utf8mb4"
    }

    try:
        conn = pymysql.connect(**db_settings)
        cursor = conn.cursor()

        today = datetime.date.today()

        # 檢查今天是否已有題目
        cursor.execute("SELECT question_id FROM question_of_the_day WHERE date = %s", (today,))
        existing = cursor.fetchone()

        if existing:
            return {
                "status": "already_exists",
                "question_id": existing[0],
                "date": str(today)
            }

        # 查詢題目總數
        cursor.execute("SELECT COUNT(*) FROM questions")
        (count,) = cursor.fetchone()

        if count == 0:
            return {"status": "no_questions_found"}

        # 隨機選一題
        random_id = random.randint(1, count)

        # 插入今日題目
        cursor.execute("""
            INSERT INTO question_of_the_day (date, question_id)
            VALUES (%s, %s)
        """, (today, random_id))
        conn.commit()

        return {
            "status": "success",
            "question_id": random_id,
            "date": str(today)
        }

    except Exception as e:
        return {"status": "error", "message": str(e)}

    finally:
        if cursor:
            cursor.close()
        if conn:
            conn.close()

if __name__ == "__main__":
    # locaL testing
    event = {}
    context = None
    response = lambda_handler(event, context)
    print(response)