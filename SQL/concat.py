import os

ORDER = {"Table" : "0","Peio" : "1", "Tintin" : "2", "Lina" : "3", "Seb" : "4"}

GROUP = "./SQL/Group/"

def concat_person(name):

    path = "./SQL/" + name + "/"
    print(os.getcwd())
    files = list(os.scandir(path))
    files_name = list(map(lambda x:x.name, files))

    file = open(GROUP +  ORDER[name] + "_" + name + ".sql", "w+", encoding="utf-8")

    for file_name in files_name:
        f = open(path + file_name, "r+", encoding="utf-8")
        file.write(f"/* {file_name} */\n")
        file.write(f.read()[:-2] + ";\n")
        file.write("\n")
        f.close()

    file.close()

def concat_all():
    path = GROUP

    files = list(os.scandir(path))
    files_name = list(map(lambda x:x.name, files))

    file = open("./SQL/BASE_ALL.sql", "w+", encoding="utf-8")

    for file_name in files_name:
        f = open(path + file_name, "r+", encoding="utf-8")
        file.write(f"/* {file_name} */\n")
        file.write(f.read()[:-2] + ";\n")
        file.write("\n")
        f.close()

    file.close()

def main():
    for i in ORDER.keys():
        print(f'CONCAT {i}')
        concat_person(i)
    print("CONCAT ALL")
    concat_all()

main()