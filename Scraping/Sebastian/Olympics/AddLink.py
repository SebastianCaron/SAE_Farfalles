from bs4 import BeautifulSoup as Bs

from selenium import webdriver
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC

import os
import time
import json
import concurrent.futures

import RegenerateSQL
from Athletes import *

def add_link(file_name):
    print(get_sport_name(file_name))
    if(not os.path.isfile(f'./JSON_Athletes/{get_sport_name(file_name)}.json')):
        print("NO FILE")
        return
    json_file = open(f'./JSON_Athletes/{get_sport_name(file_name)}.json', "r+", encoding="utf-8")
    file = open(f'./HTML_Athletes/{file_name}', 'r+', encoding="utf-8")
    
    soup = Bs(file.read().strip(), "html.parser")
    # print(json_file.read())
    aths = json.loads(json_file.read().strip())
    json_file.close()
    json_file = open(f'./JSON_Athletes/{get_sport_name(file_name)}.json', "w+", encoding="utf-8")
    for i in range(len(aths)):
        aths[i] = to_athlete(aths[i])
    athletes_html = soup.findAll('div', attrs={'class' : 'athlete'})
    taille = len(athletes_html)
    for i in range(len(athletes_html)):
        ath = aths[i]
        soupt = Bs(str(athletes_html[i]), "html.parser")
        elt = soupt.findAll('a', attrs={'class' : 'athlete__link'})
        link = "https://www.nbcolympics.com" + elt[0]["href"]
        ath.link = link
        #print(f'{file_name} : {i+1} / {taille}')
    
    str_json = json.dumps(list(map(to_dict, aths)), indent=4)

    json_file.write(str_json)
    json_file.close()

    file.close()

    RegenerateSQL.from_json_to_sql(file_name.replace("html", "json"))


def main():
    files = list(os.scandir("./HTML_Athletes/"))
    files_name = list(map(lambda x:x.name, files))
    # for f in files_name:
    #     add_link(f)
    try:
        with concurrent.futures.ThreadPoolExecutor(max_workers=6) as executor:
            executor.map(add_link, files_name)
    except:
        print("Error")

if __name__ == '__main__':
    main()