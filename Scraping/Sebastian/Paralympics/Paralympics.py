'''
pip install bs4
pip install selenium
'''


from bs4 import BeautifulSoup as Bs

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

import os
import time
import json
import concurrent.futures

from datetime import datetime

sports_dict = {
    "FEN": "fencing",
    "TKW": "taekwondo",
    "JUD": "judo",
    "WRE": "wrestling",
    "TEN": "tennis",
    "BOX": "boxing",
    "SRF": "surfing",
    "JUD_PARA": "para-judo",
    "WRU_PARA": "wheelchair-rugby",
    "VBS_PARA": "sitting-volleyball",
    "SWM_PARA": "para-swimming",
    "ROW_PARA": "para-rowing",
    "CSP_PARA": "para-canoe",
    "BDM": "badminton",
    "GRY": "rhythmic-gymnastics",
    "BKB": "basketball",
    "GAR": "artistic-gymnastics",
    "GTR": "trampoline-gymnastics",
    "FBL": "football",
    "TRI_PARA": "para-triathlon",
    "VVO": "volleyball",
    "MDN": "modern-pentathlon",
    "ROW": "rowing",
    "CSP": "canoe-sprint",
    "ATH_PARA": "athletics",
    "CRD_PARA": "para-road-cycling",
    "FBB_PARA": "blind-football",
    "TTE": "table-tennis",
    "ATH": "athletics",
    "RU7": "rugby-sevens",
    "MTB": "mountain-biking",
    "HBL": "handball",
    "SAL": "sailing",
    "TTE_PARA": "para-table-tennis",
    "WLF": "weightlifting",
    "VBV": "beach-volleyball",
    "CRD": "road-cycling",
    "HOC": "hockey",
    "BDM_PARA": "para-badminton",
    "PWL_PARA": "para-powerlifting",
    "GBL_PARA": "goalball",
    "CLB": "climbing",
    "GLF": "golf",
    "CTR_PARA": "para-track-cycling",
    "EDR": "equestrian-dressage",
    "EVE": "equestrian-eventing",
    "EJP": "equestrian-jumping",
    "MPN": "modern-pentathlon",
    "CTR": "track-cycling",
    "EQU_PARA": "para-equestrian",
    "ARC": "archery",
    "SWM": "swimming",
    "WPO": "water-polo",
    "BMX": "bmx-racing",
    "SHO": "shooting",
    "SHO_PARA": "para-shooting",
    "BK3": "3x3-basketball",
    "BKG": "breaking",
    "BMF": "bmx-freestyle",
    "SKB": "skateboard-street",
    "OWS": "open-water-swimming",
    "TRI": "triathlon",
    "SWA": "artistic-swimming",
    "DIV": "diving",
    "CSL": "canoe-slalom",
    "WFE_PARA": "wheelchair-fencing",
    "TKW_PARA": "para-taekwondo",
    "WTE_PARA": "wheelchair-tennis",
    "ARC_PARA": "para-archery",
    "BOC_PARA": "boccia",
    "WBK_PARA": "wheelchair-basketball"
}

sports_dict_reversed = {}
for (key, value) in sports_dict.items():
    sports_dict_reversed[value] = key


class Athlete:
    def __init__(self, img_url = "NULL", link = "NULL", nom = "NULL", sport = "NULL", country = "NULL", date = "NULL", lieu = "NULL", height = "NULL") -> None:
        self.img = img_url
        self.link = link
        self.nom = nom
        self.sport = sport
        self.country = country
        self.birth = date
        self.birthPlace = lieu
        self.height = height
        self.medals = []
    def __str__(self) -> str:
        return f"INSERT INTO Athletes(Image_url_Athletes, Profil_url_Athletes, Nom_Athletes, ID_Epreuves, ID, Date_naissance_Athletes, Lieu_naissance_Athletes, Taille_Athletes) VALUES ('{self.img}', '{self.link}', '{self.nom}','{self.sport}','{self.country}', '{self.birth}', '{self.birthPlace}', '{self.height}');"

def get_sport_name(file_name):
    return file_name.replace(".html", "").replace("athletes_list", "")

def to_dict(ath : Athlete):
    return {"IMG" : ath.img, "LIEN" : ath.link, "NOM" : ath.nom, "SPORT" : ath.sport, "PAYS" : ath.country, "DATE_NAISSANCE" : ath.birth, "LIEU_NAISSANCE" : ath.birthPlace, "TAILLE" : ath.height}
def to_athlete(d : dict):
    return Athlete(d["IMG"], d["LIEN"], d["NOM"], d["SPORT"], d["PAYS"], d["DATE_NAISSANCE"], d["LIEU_NAISSANCE"], d["TAILLE"])

def treatment(s):
    return s.replace("'", "''")

def contains_letters(input_string):
    return any(char.isalpha() for char in input_string)

def treatment_athletes(ath : Athlete):
    ath.birth = treatment(ath.birth)
    ath.birthPlace = treatment(ath.birthPlace)
    ath.country = treatment(ath.country)
    ath.nom = treatment(ath.nom)
    ath.height = treatment(ath.height)
    ath.sport = treatment(ath.sport)
    return ath

dict_pays = {"NULL" : "NULL", "NewZealand" : "NZL", "Canada" : "CAN", "Iranian" : "IRN", "Denmark" : "DNK", "USAsquare" : "USA", "Japanese" : "JPN", "Russia" : "RUS", "GreatBritain''s" : "GBR", "Swedish" : "SWE", "Romanian" : "ROU", "German" : "DEU", "Italian" : "ITA", "Slovakia" : "SVK", "Croatian" : "HRV", "Brazilian" : "BRA", "Ukrainian" : "UKR", "Polish" : "POL", "Czech" : "CZE", "French" : "FRA", "Mexican" : "MEX", "Austrian" : "AUT", "Spanish" : "ESP", "Hungarian" : "HUN", "China" : "CHN", "Netherlands" : "NLD", "Australia" : "AUS", "Swiss" : "CHE", "RepublicofKorea''s" : "PRK", "KenyanFlag" : "KEN", "Azerbaijani" : "AZE", "Chilean" : "CHL", "Greek" : "GRC", "Bosnian" : "BIH",
             "ArmeniaFlag" : "ARM", "Norwegian" : "NOR", "Turkish" : "TUR", "Icelandic" : "ISL", "Tajikistan_1.jpg" : "TJK", "Belgian" :"BEL", "Slovenian" : "SVN", "Serbian" : "SRB", "Finnish" : "FIN", "PuertoRican" : "PRI", "AndorraFlag" : "AND", "Liechtenstein''s" : "LIE", "MongoliaFlag" : "MNG", "Israeli" : "ISR", "Bulgarian" : "BGR", "UnitedArabEmirates''" : "ARE", "India''s" : "IND", "Cameroon.jpg" : "CMR", "Iraqi" : "IRQ", "Tunisia.jpg" : "TUN", "Syria.jpg" : "SYR", "Jordanian": "JOR", "Algerian" : "DZA", "Moroccan" : "MAR", "Egyptian" : "EGY", "senegal.jpg" : "SEN", "CIV_0.jpg" : "CIV", "Indonesian" : "IDN", "Malaysian" : "MYS",
             "AfghanistanFlag" : "AFG", "Kuwaiti" : "KWT", "Qatar''s" : "QAT", "Uzbek" : "UZB", "Bahrain''s" : "BHR", "angola.jpg" : "AGO", "Venezuela.jpg" : "VEN", "Burundi.jpg" : "BDI", "Portuguese" : "PRT", "CostoRicanFlag.jpg" : "CRI", "Dominican" : "DMA", "Thai" : "THA", "Argentinasquare" : "ARG", "Libyan" : "LBY", "Latvian" : "LVA", "Irish" : "IRL", "Kazakh" : "KAZ", "Ceylonese" : "LKA", "Trinidad&Tobago.jpg" : "TTO", "Nicaraguan" : "NIC", "Uganda" : "UGA", "COG.jpg" : "COG", "Salvadoran" : "SLV", "FlagSaoTome&Principe" : "STP", "Ecuadorian" : "ECU", "Colombian" : "COL", "Namibian" : "NAM", "Belarusian" : "BLR", "Jamaican" : "JAM", "Panamanian" : "PAN", "Mauritian" : "MRT", "SouthAfrican" : "ZAF", "CapeVerde_0.jpg" : "CPV", "Cypriot" : "CYP", "Moldovan" : "MDA", "Lithuanian" : "LTU", "SalomonIslandsFlag" : "SLB", "Philippine" : "PHL",
             "Peruvian" : "PER", "Cuba.jpg" : "CUB", "Guinea-Bissau.jpg" : "GNB", "Singapore" : "SGP", "Pakistan" : "PAK", "Togo.jpg" : "TGO", "Nigerian" : "NGA", "Maltese" : "MLT", "PapuaNewGuinea''s" : "PNG", "KyrgyzRepublic" : "KGZ", "LebanonFlag" : "LBN", "Burmese2" : "MMR", "FlagofCambodia" : "KHM", "Vietnam.jpg" : "VNM", "Nepal": "NPL", "LesothoFlag" : "LSO", "Laotian" : "LAO", "Guatemalan" : "GTM", "ChineseTaipei" : "TWN", "Honduran" : "HND", "MozambiqueFlag" : "MOZ", "HongKong''s" : "HKG", "MAC-Macao-Macao_1_11zon_0.jpg" : "MAC", "Zambia.jpg" : "ZMB", "Bhutan.jpg" : "BTN", "Suriname.jpg" : "SUR", "DemocraticPeople''sRepublicofKorea" : "KOR", "Rwandan" : "RWA", "CAF_1.jpg" : "CAF", "Benin.jpg" : "BEN", "Montenegrin" : "MNE", "Georgian" : "GEO", "Macedonia" : "MKD", "GambiaFlag" : "GMB", "Vanuatu.jpg" : "VUT", "Fijian" : "FJI", "Brunei''s" : "BRN", "Uruguayan" : "URY",
             "Estonian" : "EST", "Tanzania.jpg" : "TZA", "Malawi" : "MWI", "Omani" : "OMN", "Ghanaian" : "GHA", "Burkina_Faso.jpg" : "BFA", "Ethiopian" : "ETH", "GuineaFlag" : "GIN", "FlagGrenada" : "GRD", "DRCongoFlag" : "COD", "Bermuda.jpg" : "BMU", "MaliFlag" : "MLI", "Timorese" : "TLS", "Paraguay" : "PRY", "Zimbabwe.jpg" : "ZWE", "Aruba_1.jpg" : "ABW", "botswana.jpg" : "BWA", "Luxembourg''s" : "LUX", "Yemen.jpg" : "YEM", "SierraLeoneFlag" :"SLE", "Turkmenistan.jpg" : "TKM", "Liberian" : "LBR", "Kiribati" : "KIR", "StVincent&theGrenadines-" : "VCT", "Faroese" : "FRO"}
print(len(dict_pays))
def get_pays(s):
    return dict_pays[s]

sports = ['Alpine Skiing', 'Archery', 'Athletics', 'Badminton', 'Basketball ID', 'Biathlon', 'Blind Football', 'Boccia', 'Canoe', 'Cross-Country Skiing', 'Cycling', 'Dartchery', 'Equestrian', 'Football 7-a-side', 'Goalball', 'Ice Sledge Racing', 'Judo', 'Lawn Bowls', 'Nordic Skiing', 'Para dance sport', 'Para Ice Hockey', 'Powerlifting', 'Rowing', 'Sailing', 'Shooting', 'Sitting Volleyball', 'Snooker', 'Snowboard', 'Swimming', 'Table Tennis', 'Taekwondo', 'Triathlon', 'Weightlifting', 'Wheelchair Basketball', 'Wheelchair Curling', 'Wheelchair Fencing', 'Wheelchair Rugby', 'Wheelchair Tennis', 'Wrestling']
sports = ['Alpine Skiing','Badminton', 'Basketball ID', 'Boccia', 'Canoe','Cycling', 'Equestrian', 'Football 7-a-side', 'Goalball', 'Ice Sledge Racing', 'Judo', 'Nordic Skiing', 'Para dance sport', 'Para Ice Hockey', 'Powerlifting', 'Rowing', 'Sailing', 'Shooting', 'Sitting Volleyball', 'Snowboard', 'Swimming', 'Table Tennis','Triathlon','Wheelchair Basketball', 'Wheelchair Curling', 'Wheelchair Fencing', 'Wheelchair Rugby', 'Wheelchair Tennis', 'Athletics']

def get_athletes(sport_name):
    driver = webdriver.Firefox()

    driver.get("https://www.paralympic.org/athletes")


    time.sleep(4)
    cookies = driver.find_elements(By.CSS_SELECTOR, "#CybotCookiebotDialogBodyLevelButtonLevelOptinAllowAll")
    cookies[0].click()
    time.sleep(4)
    selecter = driver.find_elements(By.CSS_SELECTOR, "#edit-field-taxonomy-sport-target-id")[0]
    selecter.click()

    options = driver.find_elements(By.CSS_SELECTOR, "#edit-field-taxonomy-sport-target-id > option")
    found = False
    for option in options:
        if(option.text == sport_name):
            option.click()
            found = True
            break
    if not found:
        driver.quit()
        return

    active = driver.find_elements(By.CSS_SELECTOR, "#edit-field-athlete-active-value-1")[0]
    active.click()
    time.sleep(2)
    submit = driver.find_elements(By.CSS_SELECTOR, "#edit-submit-athlete-search")[0]
    submit.click()
    time.sleep(4)
    found = True
    count = 0
    while found:
        load = driver.find_elements(By.CSS_SELECTOR, ".pager__item > .button")
        found = len(load) > 0
        if(not found):
            break
        load = load[-1]
        load.click()
        time.sleep(6)
        count += 1
    
    sql = open(f'./SQL_Athletes/{sport_name.replace(" ", "-")}.sql', "+a", encoding="utf-8")
    sql.write("INSERT INTO Athletes(image, lien, nom, sport, pays, dateNaissance, lieuNaissance, taille) VALUES \n")
    json_file = open(f'./JSON_Athletes/{sport_name.replace(" ", "-")}.json', "+a", encoding="utf-8")
    athletes = driver.find_elements(By.CSS_SELECTOR, ".views-row")
    #print(athletes[0])
    aths = []
    for i in range(len(athletes)-1):
        ath = Athlete()
        soupt = Bs(athletes[i].get_attribute("outerHTML"),"html.parser")
        img = soupt.findAll('img', attrs={'class' : 'image-style-image-crop-1-1-200x200-'})
        if(len(img) > 0):
            ath.img = img[0]["src"]
        flag = soupt.findAll('article', attrs={'class' : 'media media--type-image media--view-mode-logo-image'})
        if(len(flag) > 0):
            pays = flag[0]["aria-label"].replace(" flag", "").replace(" ", "")
            ath.country = get_pays(pays)
        nom = soupt.findAll('span', attrs={'class' : 'field field--name-title field--type-string field--label-hidden'})
        if(len(nom) > 0):
            ath.nom = nom[0].text
        ath.sport = sport_name
        link = soupt.findAll('a')
        if(len(link) > 0):
            ath.link = "https://www.paralympic.org/" + link[0]["href"]
        ath = treatment_athletes(ath)
        sql.write(str(ath) + ",\n")
        aths.append(ath)
        print(f'{sport_name} : {i+1} / {len(athletes)}')
    

    ath = Athlete()
    soupt = Bs(athletes[-1].get_attribute("outerHTML"),"html.parser")
    img = soupt.findAll('img', attrs={'class' : 'image-style-image-crop-1-1-200x200-'})
    if(len(img) > 0):
        ath.img = img[0]["src"]
    flag = soupt.findAll('article', attrs={'class' : 'media media--type-image media--view-mode-logo-image'})
    if(len(flag) > 0):
        ath.country = flag[0]["aria-label"].replace(" flag", "")
    nom = soupt.findAll('span', attrs={'class' : 'field field--name-title field--type-string field--label-hidden'})
    if(len(nom) > 0):
        ath.nom = nom[0].text
    ath.sport = sport_name
    link = soupt.findAll('a')
    if(len(link) > 0):
        ath.link = "https://www.paralympic.org/" + link[0]["href"]
    ath = treatment_athletes(ath)
    sql.write(str(ath) + ",\n")
    aths.append(ath)

    json_file.write(json.dumps(list(map(to_dict, aths)), indent=4))

    sql.close()
    json_file.close()
    driver.close()

def from_json_to_sql(file_name):
    file_json = open("./JSON_Athletes/" + file_name, "r+", encoding="utf-8")
    file_sql = open("./SQL_Athletes/" + file_name.replace("json", "sql"), "w+", encoding="utf-8")
    
    aths = json.loads(file_json.read())
    for i in range(len(aths)):
        aths[i] = to_athlete(aths[i])
        if("https://www.paralympic.org" not in aths[i].img):
            aths[i].img = "https://www.paralympic.org" + aths[i].img
        if(not contains_letters(aths[i].birth)):
            date_object = datetime.strptime(aths[i].birth, "%B %d, %Y")
            aths[i].birth = date_object.strftime("%Y-%m-%d")
            
        aths[i].country = aths[i].country.replace(" ", "")
        if(aths[i].country not in dict_pays.values()):
            aths[i].country = get_pays(aths[i].country)
        if(aths[i].sport.lower() in sports_dict_reversed):
            aths[i].sport = sports_dict_reversed[aths[i].sport.lower()] + "_PARA"
    aths = list(map(treatment_athletes, aths))
    file_json.close()

    for i in range(len(aths)-1):
        file_sql.write(str(aths[i]) + "\n")
    file_sql.write(str(aths[-1]))
    
    file_sql.close()

def regenerate_sql():
    if(not os.path.isdir("./JSON_Athletes/")):
        return
    files = list(os.scandir("./JSON_Athletes/"))
    files_name = list(map(lambda x:x.name, files))

    for file_name in files_name:
        from_json_to_sql(file_name)

def main():
    os.makedirs("JSON_Athletes")
    os.makedirs("SQL_Athletes")

    # get_athletes(sports[0])
    try:
        with concurrent.futures.ThreadPoolExecutor(max_workers=5) as executor:
            executor.map(get_athletes, sports)
    except:
        print ("Error")

    # for i in range(len(sports)):
    #     get_athletes(sports[i])





if __name__ == '__main__':
    #main()
    regenerate_sql()