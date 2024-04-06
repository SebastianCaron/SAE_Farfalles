import os

#ORDER = {"Tables" : "0","Peio" : "1", "Tintin" : "2", "Lina" : "3", "Seb" : "4"}
ORDER = {"Tables" : "0","Peio" : "1", "Tintin" : "2", "Lina" : "3"}


GROUP = os.getcwd() + "/SQL/Group/"

if(not os.path.isdir(GROUP)):
    os.makedirs(GROUP)

def concat_person(name):

    path = os.getcwd() + "/SQL/" + name + "/"
    files = list(os.scandir(path))
    files_name = list(map(lambda x:x.name, files))

    file = open(GROUP +  ORDER[name] + "_" + name + ".sql", "w", encoding="utf-8")

    for file_name in files_name:
        f = open(path + file_name, "r+", encoding="utf-8")
        file.write(f"/* {file_name} */\n")
        file.write(f.read()+ "\n")
        f.close()

    file.close()

def concat_all():
    path = GROUP

    files = list(os.scandir(path))
    files_name = list(map(lambda x:x.name, files))

    file = open(os.getcwd() + "/SQL/BASE_ALL.sql", "w", encoding="utf-8")

    for file_name in files_name:
        f = open(path + file_name, "r+", encoding="utf-8")
        file.write(f"/* {file_name} */\n")
        file.write(f.read() + "\n")
        f.close()

    file.close()

def main():
    for i in ORDER.keys():
        print(f'CONCAT {i}')
        concat_person(i)
    #print("CONCAT ALL")
    #concat_all()

main()