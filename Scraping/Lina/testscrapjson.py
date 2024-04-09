import requests
import json
import os

l_epreuves = []

def scrapjson(url):
    x = 0
    response = requests.get(url)
    data = response.json()
    
    if not os.path.exists("data"):
        os.makedirs("data")
    
    for item in data:
        code_site = item.get("code_site")
        nom_site = item.get("nom_site")
        category_id = item.get("category_id")
        sports = item.get("sports")
        start_date = item.get("start_date")
        end_date = item.get("end_date")
        adress = item.get("adress")
        latitude = item.get("latitude")
        longitude = item.get("longitude")
        point_geo = item.get("point_geo")
        
        site_data = {
            "code_site": code_site,
            "nom_site": nom_site,
            "category_id": category_id,
            "sports": sports,
            "start_date": start_date,
            "end_date": end_date,
            "adress": adress,
            "latitude": latitude,
            "longitude": longitude,
            "point_geo": point_geo
        }
        l_virg = []
        categ = category_id[6:]
        #file = open(f"data/epreuves2.txt", "a+")
        file = open(f"data/avoirlieu.txt", "a+")
        prec=0
        if ',' in sports :
            for i in range (len(sports)) :
                if sports[i] == "," :
                    l_virg.append(i)
            
            for j in l_virg :
                id_ep = sports[j-4]+sports[j-3]+sports[j-2]
                if categ == "paralympic" :
                    id_ep += "_PARA"
#                 if not(id_ep in l_epreuves) :
#                     l_epreuves.append(id_ep)
#                     str_j = "INSERT INTO Epreuves(ID_Epreuves, Nom_Epreuves, Catégorie_Epreuves, Logo_Epreuves) VALUES (\""+id_ep+"\",\""+sports[prec:j-6]+"\",\""+categ+"\",\"logo\"),\n"
#                     print(str_j)
#                     file.write(str_j)
                str_j = "INSERT INTO Avoirlieu(ID_Avoirlieu, ID_Epreuves, Date_debut, Date_fin, Latitude_Sites, Longitude_Sites) VALUES ("+str(x)+", \""+id_ep+"\", \""+start_date+"\", \""+end_date+"\", "+latitude+", "+longitude+"),\n"
                print(str_j)
                x+=1
                file.write(str_j)
                prec = j+1
        
        id_ep = sports[-4]+sports[-3]+sports[-2]
        if categ == "paralympic" :
            id_ep += "_PARA"
#         if not(id_ep in l_epreuves) :
#             l_epreuves.append(id_ep)
#             str_json = "INSERT INTO Epreuves(ID_Epreuves, Nom_Epreuves, Catégorie_Epreuves, Logo_Epreuves) VALUES (\""+id_ep+"\",\""+sports[prec:-6]+"\",\""+categ+"\",\"logo\"),\n"
#             print(str_json)
#             file.write(str_json)
        str_json = "INSERT INTO Avoirlieu(ID_Avoirlieu, ID_Epreuves, Date_debut, Date_fin, Latitude_Sites, Longitude_Sites) VALUES ("+str(x)+", \""+id_ep+"\", \""+start_date+"\", \""+end_date+"\", "+latitude+", "+longitude+"),\n"
        print(str_json)
        x+=1
        file.write(str_json)
# CALENDRIER ABANDONNE
#         file = open(f"data/calendrier.txt", "a+")
#         str_calen = "INSERT INTO Calendrier(ID_Calendrier, date_deb, date_fin) VALUES ("+str({"start_date":start_date, "end_date":end_date})+",\""+start_date+"\",\""+end_date+"\"),\n"
#         print(str_calen)
#         file.write(str_calen)
#         file = open(f"data/ceremonies.txt", "a+")
#         file = open(f"data/ceremonies.txt", "a+")
#         tex = "INSERT INTO Ceremonies (ID_Ceremonies, Nom_Ceremonie) VALUES (\"\",\"\",\"\",\""+str({"start_date":start_date, "end_date":end_date})+"\"), \nNOM EPREUVES : "+sports+"\nNOM SITE :"+nom_site+"      POINT GEO :"+str(point_geo)+"\n\n"
#         print(tex)
#         file.write(tex)
scrapjson("https://data.paris2024.org/api/explore/v2.1/catalog/datasets/paris-2024-sites-de-competition/exports/json")


