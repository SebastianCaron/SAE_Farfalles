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



class Athlete:
    def __init__(self, img_url = None, link = None, nom = None, sport = None, country = None, date = "NULL", lieu = "NULL", height = "NULL") -> None:
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

def get_athletes_from_file(file_name):
    sql = open(f'./SQL_Athletes/{get_sport_name(file_name)}.sql', "+a", encoding="utf-8")
    sql.write("INSERT INTO Athletes(image, lien, nom, sport, pays, dateNaissance, lieuNaissance, taille) VALUES \n")
    json_file = open(f'./JSON_Athletes/{get_sport_name(file_name)}.json', "+a", encoding="utf-8")

    driver = webdriver.Firefox()
    file = open(f'./HTML_Athletes/{file_name}', 'r+', encoding="utf-8")
    soup = Bs(file.read() , "html.parser")

    aths = []
    athletes_html = soup.findAll('div', attrs={'class' : 'athlete'})
    taille = len(athletes_html)
    for i in range(len(athletes_html)):
        ath = Athlete()
        soupt = Bs(str(athletes_html[i]), "html.parser")
        elt = soupt.findAll('img', attrs={'class' : 'athlete__headshot-img'})
        ath.img = elt[0]["src"]
        elt = soupt.findAll('div', attrs={'class' : 'athlete__name'})
        ath.nom = elt[0].text.replace("'", "''")
        elt = soupt.findAll('div', attrs={'class' : 'athlete__sport'})
        ath.sport = elt[0].text
        elt = soupt.findAll('div', attrs={'class' : 'athlete__country-abbr'})
        ath.country = elt[0].text

        elt = soupt.findAll('a', attrs={'class' : 'athlete__link'})
        link = elt[0]["href"]
        # response = requests.get('https://www.nbcolympics.com' + link)
        

        driver.get('https://www.nbcolympics.com' + link)

        html = driver.page_source

        # print(response.content)
        soupt = Bs(html, "html.parser")
        details = soupt.findAll('div', attrs={'class' : 'athlete-profile__details-content'})
        if(len(details) > 0):
            ath.birth = details[0].text.replace("'", "''")
        if(len(details) > 2):
            ath.birthPlace = details[2].text.replace("'", "''")
        if(len(details) > 1):
            ath.height = str(details[1].text).replace("'", "''")
        ath.link = link

        medals = soupt.findAll('span', attrs={'class' : 'widget-medalcountathlete__table__body__row__cell__medals__col__amount'})
        # print(medals)
        # medals = list(map(lambda x:x.text, medals))
        # ath.medals = medals

        aths.append(ath)
        # print(ath)
        sql.write(str(ath) + ",\n")
        print(f'{file_name} : {i+1} / {taille}')
    driver.quit()
    sql.write(";")
    sql.close()
    
    str_json = json.dumps(list(map(to_dict, aths)), indent=4)

    json_file.write(str_json)
    json_file.close()

    file.close()

def get_html(sport_name):
    driver = webdriver.Firefox()
    driver.get('https://www.nbcolympics.com/athletes?sport=' + sport_name)
    file_html = open(f"./HTML_Athletes/{sport_name}.html", "w+", encoding="utf-8")
    file_txt = open(f"./TXT_Athletes/{sport_name}.txt", "w+", encoding="utf-8")
    found = True
    time.sleep(3)
    cookies = driver.find_elements(By.CSS_SELECTOR, "#onetrust-accept-btn-handler")
    if(len(cookies) > 0):
        cookies[0].click()
        time.sleep(2)
    try:
        wait = WebDriverWait(driver, 10)
        while found:
            try:
                cta_elements = driver.find_elements(By.CSS_SELECTOR, ".cta__text")
                #print(cta_elements)
                if len(cta_elements) >= 3:
                    cta_elements[2].click()
                    found =  True
                else:
                    found = False
            except:
                found = False
                print("pas trouve")
            time.sleep(3)
        txt = wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".athletes__list.athletes-browser-athletes__list"))).text
        file_txt.write(txt)
        html = wait.until(EC.visibility_of_element_located((By.CSS_SELECTOR, ".athletes__list.athletes-browser-athletes__list"))).get_attribute("outerHTML")
        file_html.write(html)
    except:
        print("Error")
    driver.quit()
    file_txt.close()
    file_html.close()


sportNames = ['alpine-skiing', 'archery', 'artistic-swimming', 'badminton', 'baseball', 'basketball-3x3', 'basketball', 'beach-volleyball', 'biathlon', 'bobsled', 'boxing', 'canoeing', 'cross-country-skiing', 'curling', 'cycling', 'diving', 'equestrian', 'fencing', 'field-hockey', 'figure-skating', 'freestyle-skiing', 'golf', 'gymnastics', 'handball', 'hockey', 'judo', 'karate', 'luge', 'modern-pentathlon', 'nordic-combined', 'rhythmic-gymnastics', 'rowing', 'rugby', 'sailing', 'shooting', 'short-track', 'skateboarding', 'skeleton', 'ski-jumping', 'snowboarding', 'soccer', 'softball', 'speed-skating', 'sport-climbing', 'surfing', 'table-tennis', 'taekwondo', 'tennis','track-field','trampoline', 'triathlon', 'volleyball', 'water-polo', 'weightlifting', 'wrestling']

def main():
    if(not os.path.isdir("HTML_Athletes")):
        os.makedirs("HTML_Athletes")
        os.makedirs("TXT_Athletes")
        try:
            with concurrent.futures.ThreadPoolExecutor(max_workers=4) as executor:
                executor.map(get_html, sportNames)
        except:
            print("Error")

    # Simple
    # for name in sportNames:
    #     get_html(name)

    files = list(os.scandir("./HTML_Athletes/"))
    os.makedirs("JSON_Athletes")
    os.makedirs("SQL_Athletes")
    files_name = list(map(lambda x:x.name, files))
    try:
        with concurrent.futures.ThreadPoolExecutor(max_workers=6) as executor:
            executor.map(get_athletes_from_file, files_name)
    except:
        print ("Error")

if __name__ == '__main__':
    main()