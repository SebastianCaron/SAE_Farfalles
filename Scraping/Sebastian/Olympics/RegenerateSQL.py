import os
import time
import json
import concurrent.futures
import Athletes

from datetime import datetime

CONCAT_IN_ONE_FILE = True

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


def treatment(s):
    return s.replace("'", "''")

def contains_letters(input_string):
    return any(char.isalpha() for char in input_string)

def treatment_athletes(ath : Athletes.Athlete):
    ath.birth = treatment(ath.birth)
    ath.birthPlace = treatment(ath.birthPlace)
    ath.country = treatment(ath.country)
    ath.nom = treatment(ath.nom)
    ath.height = treatment(ath.height)
    ath.sport = treatment(ath.sport)
    return ath

def from_json_to_sql(file_name):
    file_json = open("./JSON_Athletes/" + file_name, "r+", encoding="utf-8")
    file_sql = open("./SQL_Athletes/" + file_name.replace("json", "sql"), "w+", encoding="utf-8")

    
    aths = json.loads(file_json.read())
    for i in range(len(aths)):
        aths[i] = Athletes.to_athlete(aths[i])
        if("https:" not in aths[i].img):
            aths[i].img = "https:" + aths[i].img
        if(contains_letters(aths[i].birth)):
            date_object = aths[i].birth
            try:
                date_object = datetime.strptime(aths[i].birth, "%B %d, %Y")
                aths[i].birth = str(date_object.strftime("%Y-%m-%d"))
            except:
                print(aths[i].birth)
        if(str(aths[i].sport).lower() in sports_dict_reversed):
            aths[i].sport = sports_dict_reversed[aths[i].sport.lower()]
    aths = list(map(treatment_athletes, aths))
    file_json.close()

    for i in range(len(aths)-1):
        file_sql.write(str(aths[i]) + "\n")
    file_sql.write(str(aths[-1]))
    
    file_sql.close()
    
    
def main():
    if(not os.path.isdir("./JSON_Athletes/")):
        return
    files = list(os.scandir("./JSON_Athletes/"))

    files_name = list(map(lambda x:x.name, files))

    for file_name in files_name:
        from_json_to_sql(file_name)

    if(CONCAT_IN_ONE_FILE):
        files = list(os.scandir("./SQL_Athletes/"))
        files_name = list(map(lambda x:x.name, files))
        file_all = open("./SQL_Athletes/all.sql", "w", encoding="utf-8")
        s = ""
        for f in files_name:
            file = open("./SQL_Athletes/" + f, "r", encoding="utf-8")
            s += f"/* {f.replace('.sql', '').upper()} */\n"
            s += file.read() + "\n\n"
            file.close()
        file_all.write(s)
        file_all.close()



if __name__ == '__main__':
    main()
    