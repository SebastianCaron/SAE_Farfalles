import mysql.connector
from mysql.connector import Error
import os

def execute_sql_script(host, user, password, database, sql_file):
    try:
        connection = mysql.connector.connect(
            host=host,
            user=user,
            password=password,
            database=database
        )

        if connection.is_connected():
            print("Connected to MySQL database")

            with open(sql_file, 'r') as file:
                sql_script = file.read()

            cursor = connection.cursor()
            cursor.execute(sql_script)
            connection.commit()
            print("SQL script executed successfully")

    except Error as e:
        print(f"ERREUR : {e}")

    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()
            print("MySQL connection is closed")


if __name__ == "__main__":
    host = os.getenv('DB_HOST')
    user = os.getenv('DB_USERNAME')
    password = os.getenv('DB_PASSWORD')
    database = os.getenv('DB_NAME')

    path = os.getcwd() + "/SQL/Group/"
    files = list(os.scandir(path))
    files_name = list(map(lambda x:x.name, files))

    for file_name in files_name:
        execute_sql_script(host, user, password,database, path + file_name)
