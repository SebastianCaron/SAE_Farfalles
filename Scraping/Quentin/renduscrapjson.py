import requests

url = "https://www.data.gouv.fr/fr/datasets/r/521fe6f9-0f7f-4684-bb3f-7d3d88c581bb"
response = requests.get(url)
city_data = response.json()

cities_list = []
for city in city_data['cities']:
    latitude_str = city['latitude'].replace(',', '.') if city['latitude'] else '0.0'
    longitude_str = city['longitude'].replace(',', '.') if city['longitude'] else '0.0'
    city_info = {
        'insee_code': city['insee_code'],
        'city_code': city['city_code'], 
        'zip_code': city['zip_code'],
        'label': city['label'],
        'latitude': float(latitude_str),
        'longitude': float(longitude_str),
        'department_name': city['department_name'],
        'department_number': city['department_number'],
        'region_name': city['region_name'],
        'region_geojson_name': city['region_geojson_name']
    }

    cities_list.append(city_info)

sql = ""
sql2 = ""

url = "https://data.paris2024.org/api/explore/v2.1/catalog/datasets/paris-2024-sites-de-competition/exports/json"
response = requests.get(url)
site_data = response.json()

for item in site_data:
    code_site = item.get("code_site")
    nom_site = item.get("nom_site")
    category_id = item.get("category_id")
    sports = item.get("sports")
    start_date = item.get("start_date")
    end_date = item.get("end_date")
    latitude_str = item.get("latitude").replace(',', '.') if item.get("latitude") else '0.0'  
    longitude_str = item.get("longitude").replace(',', '.') if item.get("longitude") else '0.0'  
    latitude = float(latitude_str)
    longitude = float(longitude_str)
    nom_ville = None
    code_postal = None
    

    closest_city = None
    min_difference = float('inf')

    for city_info in cities_list:
        lat_diff = abs(city_info['latitude'] - latitude)
        lon_diff = abs(city_info['longitude'] - longitude)
        
        total_diff = lat_diff + lon_diff
        
        if total_diff < min_difference:
            closest_city = city_info
            min_difference = total_diff

    nom_ville = closest_city["label"]
    code_postal = closest_city["zip_code"]

    site_data = {
        "code_site": code_site,
        "nom_site": nom_site,
        "category_id": category_id,
        "sports": sports,
        "start_date": start_date,
        "end_date": end_date,
        "latitude": latitude,
        "longitude": longitude,
        "nom_ville": nom_ville,
        "code_postal" : code_postal
    }

    if(str(latitude)) not in sql:
        if nom_site == 'Tahiti Teahupo\'o':
            sql += f"INSERT INTO Sites(Latitude_Sites, Longitude_Sites, Nom_Sites, Date_de_construction_Sites, Capacite_d_acceuil_Sites, Accessibilite_Sites, Nom_Villes) VALUES ({latitude}, {longitude}, 'Tahiti Teahupo''o', {'NULL'}, {'NULL'}, {'NULL'}, '{nom_ville}');\n"
        else:
            sql += f"INSERT INTO Sites(Latitude_Sites, Longitude_Sites, Nom_Sites, Date_de_construction_Sites, Capacite_d_acceuil_Sites, Accessibilite_Sites, Nom_Villes) VALUES ({latitude}, {longitude}, '{nom_site}', {'NULL'}, {'NULL'}, {'NULL'}, '{nom_ville}');\n"

    if(code_postal) not in sql2:
        sql2+= f"INSERT INTO Villes(Nom_Villes, Code_Postal_Villes, Population_Villes) VALUES('{nom_ville}',{code_postal},{'NULL'});\n"

file = open("sitessql.sql", "w+", encoding="utf-8")
file.write(sql)
file.close()

file2 = open("villessql.sql", "w+", encoding="utf-8")
file2.write(sql2)
file2.close()