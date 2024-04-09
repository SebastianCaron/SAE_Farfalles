import os

path = "./SQL_Athletes/"

files = list(os.scandir(path))
files_name = list(map(lambda x:x.name, files))

file = open(path + "all.sql", "w+", encoding="utf-8")

for file_name in files_name:
    f = open(path + file_name, "r+", encoding="utf-8")
    file.write(f"/* {file_name} */\n")
    file.write(f.read() + "\n")
    file.write("\n")
    f.close()

file.close()

