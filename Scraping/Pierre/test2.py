import requests
from bs4 import BeautifulSoup as Bs

# On récupère la page
soup = Bs(open('./Liste de codes pays à 3 chiffres.html', 'r+', encoding="utf-8").read(), "html.parser")
soup2 = Bs(open('./List_ 3-letter country abbreviation.html', 'r+', encoding="utf-8").read(), "html.parser")

# On recupere les elements Htmls qui nous interesse
# On recupere les elements HTML qui nous intéressent
s2 = soup.findAll('table')[0]
s22 = soup2.findAll('table')[0]

s2 = Bs(str(s2), "html.parser")
s22 = Bs(str(s22), "html.parser")

s2 = s2.findAll('tr')
s22 = s22.findAll('tr')

pays = []
drapeaux = []

for i in range(len(s2)):
    k = Bs(str(s2[i]), "html.parser")
    k2 = Bs(str(s22[i]), "html.parser")

    elements_texts = k.findAll('td')
    elements_texts2 = k2.findAll('td')

    if len(elements_texts) >= 2:
        code = elements_texts[0].text.strip()
        nom_fr = elements_texts[1].text.strip()
        nom_ang = elements_texts2[1].text.strip()
        pays.append({"Code": code, "NomFr": nom_fr, "NomAng": nom_ang})

# URL du site
url = "https://www.mappi.net/list_of_countries.php"

# Effectuer la requête HTTP
response = requests.get(url)

# Vérifier si la requête a réussi
if response.status_code == 200:
    # Parser le contenu HTML avec BeautifulSoup
    soup3 = Bs(response.content, "html.parser")

    # Récupérer les éléments nécessaires
    tousLesTr = soup3.findAll('tr')

    for j in range(len(tousLesTr)):
        k22 = Bs(str(tousLesTr[j]), "html.parser")

        elements_img = k22.findAll('img')
        elements_text8 = k22.findAll('td')

        for img in elements_img:
            if 'src' in img.attrs:
                image_url = "https://mappi.net/"+img['src']
                image_url = image_url.replace("mini_", "")  
                if len(elements_text8) > 1:
                    nom_pays = elements_text8[1].text.replace('\n', ' ').strip()
                    drapeaux.append({"drapeau": image_url, "NomAng": nom_pays})

    # Afficher les informations des drapeaux
    for pay in pays:
        pay["drapeau"] = "https://upload.wikimedia.org/wikipedia/commons/a/a7/Olympic_flag.svg"
        pay["NomFr"]  = pay["NomFr"].replace("'","''")
        pay["NomFr"]  = pay["NomFr"].replace("(USA)","")
        pay["NomFr"]  = pay["NomFr"].replace("Cabo Verde","Cap-Vert")
        for drapeau in drapeaux:
            if drapeau["NomAng"]==pay["NomAng"]:
                # print(drapeau["NomAng"])
                pay["drapeau"] = drapeau["drapeau"]
            
        
        

    res = []
    # INSERT INTO Calendrier(ID, date_deb, date_fin) VALUES ({'start_date': '2024-07-27', 'end_date': '2024-08-10'},"2024-07-27","2024-08-10")

    
    for pay in pays:
        commande = "INSERT INTO Pays(ID, Drapeau, Nom_Français, Nom_Anglais) VALUES ('"+ pay["Code"]+"', '"+pay["drapeau"]+"', '"+pay["NomFr"]+"', '"+pay["NomAng"]+"');"
        res.append(commande)
    
    with open('pays.txt', 'w+',encoding='utf-8') as f:
        for resu in res:
            f.write(resu + '\n')
