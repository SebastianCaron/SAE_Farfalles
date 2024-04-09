import os

path = "./SQL_Transports/"

files = list(os.scandir(path))
files_name = list(map(lambda x:x.name, files))

file = open(path + "Transports_all.sql", "w+", encoding="utf-8")

for file_name in files_name:
    f = open(path + file_name, "r+", encoding="utf-8")
    file.write(f"/* {file_name} */\n")
    file.write(f.read()[:-2] + ";\n")
    file.write("\n")
    f.close()

file.close()


file = open(path + "Transports_small.sql", "w+", encoding="utf-8")

for file_name in files_name:
    f = open(path + file_name, "r+", encoding="utf-8")
    file.write(f"/* {file_name} */\n")
    lines = f.readlines()[:2000]
    file.writelines(lines)
    file.write(";\n")
    file.write("\n")
    f.close()

file.close()
