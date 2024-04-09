import csv
import os

gtfs_folder = './IDFM-gtfs/'

class Agence:
    def __init__(self, agency_id = "NULL", agency_name = "NULL", agency_url = "NULL", agency_timezone = "NULL", agency_lang = "NULL", agency_phone = "NULL", agency_email = "NULL", agency_fare_url = "NULL"):
        self.agency_id = agency_id
        self.agency_name = agency_name
        self.agency_url = agency_url
        self.agency_timezone = agency_timezone
        self.agency_lang = agency_lang
        self.agency_phone = agency_phone
        self.agency_email = agency_email
        self.agency_fare_url = agency_fare_url
    def __str__(self) -> str:
        return f"INSERT INTO Agences(ID_Agences, Nom_agences, Url_agences, Timezone_agences, Lang_agences, Tel_agences, Email_agences, Tarifs_Url_agences) VALUES ({self.agency_id}, {self.agency_name}, {self.agency_url}, {self.agency_timezone}, {self.agency_lang}, {self.agency_phone}, {self.agency_email}, {self.agency_fare_url});"


class Arret:
    def __init__(self, arret_id = "NULL", arret_nom = "NULL", arret_lat = "NULL", arret_long = "NULL", arret_fauteuil = "NULL", numero = "NULL", voyage = None) -> None:
        self.id = arret_id
        self.nom = arret_nom
        self.lat = arret_lat
        self.long = arret_long
        self.fauteuil = arret_fauteuil
        self.heure_depart = "NULL"
        self.heure_arrive = "NULL"
        self.voyage = voyage
        self.numero = numero

    def __str__(self) -> str:
        s1 = self.long.replace("'", "").replace('"', "")
        s2 = self.lat.replace("'", "").replace('"', "")
        s3 = self.numero.replace("'", "").replace('"', "")
        if(len(s1) == 0):
            s1 = 0
        if(len(s2) == 0):
            s2 = 0
        if(len(s3) == 0):
            s3 = 0
        return f"INSERT INTO Arrets(ID_Arrets, ID_Voyages, Nom_Arrets, Latitude_Arrets, Longitude_Arrets, Accessible_Arrets,Heure_arrive_Arrets, Heure_depart_Arrets,  Numero_Arrets) VALUES ('{self.id}', '{self.voyage.id}', {self.nom}, {float(s2)}, {float(s1)}, {self.fauteuil}, {self.heure_depart}, {self.heure_arrive}, {int(s3)});"
    
    def clone(self):
        a = Arret(self.id, self.nom, self.lat, self.long, self.fauteuil,self.numero, self.voyage)
        a.heure_arrive = self.heure_arrive
        a.heure_depart = self.heure_depart
        return a

    
class Ligne:
    def __init__(self, id = "NULL", agence = None, nom = "NULL", nom_long = "NULL") -> None:
        self.mode = "NULL"
        self.agence = agence
        self.nom = nom
        self.nom_long = nom_long
        self.id = id
    def __str__(self) -> str:
        return f"INSERT INTO Lignes(ID_Lignes, Nom_Lignes, Nom_long_Lignes, Mode_Lignes, ID_Agences) VALUES ('{self.id}', '{self.nom}', '{self.nom_long}', '{self.mode}', '{self.get_agence()}');"
    def get_agence(self):
        return self.agence
    def set_mode(self, i):
        if(i == "0"):
            self.mode = "Tramway"
        elif(i == "1"):
            self.mode = "Métro"
        elif(i == "2"):
            self.mode = "Train"
        elif(i == "3"):
            self.mode = "Bus"
        elif(i == "7"):
            self.mode = "Funiculaire"


class Voyage:
    def __init__(self, voyage_id = "NULL", ligne = "NULL", nom = "NULL", accessible_fauteuil = "NULL", accessible_velo = "NULL") -> None:
        self.id = voyage_id
        self.ligne = ligne
        self.nom = nom
        self.fauteuil = accessible_fauteuil
        self.velo = accessible_velo
        self.arrets = {}
    def __str__(self) -> str:
        return f"INSERT INTO Voyages(ID_Voyages, ID_Lignes, Nom_Voyages, Accessible_Fauteuil_Voyages, Accessible_Velo_Voyages) VALUES ('{self.id}', '{self.ligne.id}', '{self.nom}', {self.get_fauteuil()}, {self.get_velo()});"
    def get_fauteuil(self):
        if(len(self.fauteuil) == 0):
            return "NULL"
        return self.fauteuil
    def get_velo(self):
        if(len(self.velo) == 0):
            return "NULL"
        return self.velo
    
        

def treatment(s : str):
    return "'" + s.replace("\n", "").replace("'", "''") + "'"

def main():
    path = "SQL_Transports"
    os.makedirs(path)

    agences_file = open(gtfs_folder + "agency.txt", "r+", encoding="utf-8")
    agences_sql_file = open("./" + path + "/" + "0_agences.sql", "w+", encoding="utf-8")
    agences_lines = agences_file.readlines()[1:]
    agences = {}
    for line in agences_lines:
        line = line.split(",")
        line[0] = line[0].replace("IDFM:", "")
        agences[line[0]] = Agence(treatment(line[0]), treatment(line[1]), treatment(line[2]), treatment(line[3]), treatment(line[4]), treatment(line[5]), treatment(line[6]), treatment(line[7]))

    print("Ecriture Agence...")
    s = ""
    a = list(agences.values())
    for i in range(len(a)-1):
        s += str(a[i]) + "\n"
    s += str(a[-1]) + "\n"

    agences_sql_file.write(s)
    agences_sql_file.close()
    agences_file.close()

    print("Ecriture Agence terminée !")

    print("Chargement des Routes")
    routes_file = open(gtfs_folder + "routes.txt", "r+", encoding="utf-8")
    routes_sql_file = open("./" + path + "/" + "1_routes.sql", "w+", encoding="utf-8")
    routes_lines = routes_file.readlines()[1:]
    routes = {}
    for line in routes_lines:
        line = line.split(",")
        line[0] = line[0].replace("IDFM:C", "")
        line[1] = line[1].replace("IDFM:", "")
        routes[line[0]] = Ligne(line[0], line[1].replace("'", "''"), line[2].replace("'", "''"), line[3].replace("'", "''"))
        routes[line[0]].set_mode(line[5])

    print("Ecriture des Lignes...")
    s = ""
    r = list(routes.values())
    for i in range(len(r)-1):
        s += str(r[i]) + "\n"
    s += str(r[-1]) + "\n"

    routes_sql_file.write(s)
    routes_sql_file.close()
    routes_file.close()
    print("Ecriture des Lignes terminée !")


    voyages_file = open(gtfs_folder + "trips.txt", "r+", encoding="utf-8")
    voyages_sql_file = open("./" + path + "/" + "2_voyages.sql", "w+", encoding="utf-8")
    arrets_sql_file = open("./" + path + "/" + "3_arrets.sql", "w+", encoding="utf-8")
    voyages_lines = voyages_file.readlines()[1:]
    voyages = {}

    print("Chargement des Voyages...")
    for line in voyages_lines:
        line = line.split(",")
        line[2] = line[2].replace("IDFM:", "")
        line[0] = line[0].replace("IDFM:C", "")
        voyages[line[2]] = Voyage(line[2], routes[line[0]], line[3].replace("'", "''"), line[8], line[9].replace("\n", ""))


    arrets_file = open(gtfs_folder + "stops.txt", "r+", encoding="utf-8")
    arrets_temps_file = open(gtfs_folder + "stop_times.txt", "r+", encoding="utf-8")
    arrets_lines = arrets_file.readlines()[1:]
    arrets = {}
    l = len(arrets_lines)
    c = 0
    print("Chargement des Arrets...")
    for line in arrets_lines:
        line = list(map(treatment, line.split(",")))
        line[0] = line[0].replace("IDFM:", "").replace("'", "")
        # line[9] = line[9].replace("IDFM:", "").replace("'", "")
        arrets[line[0]] = Arret(line[0], line[2], line[4], line[5], line[12])
        c += 1
        if(c%100 == 0):
            print(f'{l - c}')
    
    arrets_temps = {}
    arrets_lines = arrets_temps_file.readlines()[1:]
    l = len(arrets_lines)
    c = 0
    print("Associations des Arrets, des Lignes et des heures...")
    for line in arrets_lines:
        line = list(map(treatment, line.split(",")))
        line[0] = line[0].replace("IDFM:", "").replace("'", "")
        line[3] = line[3].replace("IDFM:", "").replace("'", "")
        if(line[3] in arrets):
            arrets[line[3]].heure_depart = line[2]
            arrets[line[3]].heure_arrive = line[1]
            arrets[line[3]].voyage = voyages[line[0]]
            arrets[line[3]].numero = line[4]
            arrets_temps[line[3]] = arrets[line[3]].clone()
            voyages[line[0]].arrets[line[4]] = arrets_temps[line[3]]
        else:
            print(f'arrets : {line[3]} not found !')
        c += 1
        if(c%1000 == 0):
            print(f'{l - c}')

    print("Ecriture des Arrets...")
    s = ""
    
    arrets = list(arrets_temps.values())
    for i in range(len(arrets)-1):
        s += str(arrets[i]) + "\n"
    
    s += str(arrets[-1]) + "\n"

    arrets_sql_file.write(s)
    arrets_sql_file.close()
    print("Ecriture des Arrets terminée !")

    print("Ecriture des Voyages...")
    voyages = list(voyages.values())
    s = ""
    l = len(voyages)
    for i in range(len(voyages)-1):
        s += str(voyages[i]) + "\n"
        if(i % 1000 == 0):
            print(f'{l - i}')

    s += str(voyages[-1]) + "\n"

    voyages_sql_file.write(s)
    voyages_sql_file.close()
    print("Ecriture des Voyages terminée !")



# def display_route(gtfs_folder, route_id):
#     stops = {}
#     with open(f'{gtfs_folder}/stops.txt', 'r', encoding='utf-8') as stops_file:
#         stops_reader = csv.DictReader(stops_file)
#         for row in stops_reader:
#             stops[row['stop_id']] = row['stop_name']

#     stop_times = {}
#     with open(f'{gtfs_folder}/stop_times.txt', 'r', encoding='utf-8') as stop_times_file:
#         stop_times_reader = csv.DictReader(stop_times_file)
#         for row in stop_times_reader:
#             trip_id = row['trip_id']
#             stop_sequence = int(row['stop_sequence'])
#             stop_id = row['stop_id']
#             arrival_time = row['arrival_time']
#             if trip_id not in stop_times:
#                 stop_times[trip_id] = []
#             stop_times[trip_id].append((stop_sequence, stops[stop_id], arrival_time))

#     print(f"Route: {route_id}")
#     for trip_id, trip_stops in stop_times.items():
#         print(f"\nTrip: {trip_id}")
#         for stop_sequence, stop_name, arrival_time in sorted(trip_stops):
#             print(f"{stop_sequence}. {stop_name} ({arrival_time})")



# route_ids = set()
# with open(f'{gtfs_folder}/routes.txt', 'r', encoding='utf-8') as routes_file:
#     routes_reader = csv.DictReader(routes_file)
#     for row in routes_reader:
#         route_ids.add(row['route_id'])


# trip_route_map = {}
# with open(f'{gtfs_folder}/trips.txt', 'r', encoding='utf-8') as trips_file:
#     trips_reader = csv.DictReader(trips_file)
#     for row in trips_reader:
#         trip_route_map[row['trip_id']] = row['route_id']

# trips = set()
# with open(f'{gtfs_folder}/stop_times.txt', 'r', encoding='utf-8') as stop_times_file:
#     stop_times_reader = csv.DictReader(stop_times_file)
#     for row in stop_times_reader:
#         trips.add(row['trip_id'])

# # for trip_id in trips:
# #     route_id = trip_route_map.get(trip_id)
# #     if route_id is not None:
# #         print(f"Bus {trip_id} on route {route_id}")
# #     else:
# #         print(f"Bus {trip_id} with unknown route")


# for trip_id in list(trips)[:1]:
#     route_id = trip_route_map.get(trip_id)
#     display_route(gtfs_folder, route_id)


main()