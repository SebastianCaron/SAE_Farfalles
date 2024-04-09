import requests
import json
import os

url = "https://data.paris2024.org/api/explore/v2.1/catalog/datasets/paris-2024-sites-de-competition/exports/json"
response = requests.get(url)
data = response.json()

#Pour faire un dossier avec toutes les données séparées
if not os.path.exists("dataVillesJo"):
    os.makedirs("dataVillesJo")

for item in data:
    code_site = item.get("code_site")
    nom_site = item.get("nom_site")
    category_id = item.get("category_id")
    sports = item.get("sports")
    start_date = item.get("start_date")
    end_date = item.get("end_date")
    latitude = item.get("latitude")
    longitude = item.get("longitude")
    
    site_data = {
        "code_site": code_site,
        "nom_site": nom_site,
        "category_id": category_id, #EX : venue-olympic
        "sports": sports,
        "start_date": start_date,
        "end_date": end_date,
        "latitude": latitude,
        "longitude": longitude,
    }

    #Pour faire un dossier contenant tous les sites
    file = open(f"dataVillesJo/{code_site}.json", "w+")
    str_json = json.dumps(site_data, indent=4)
    file.write(str_json)
