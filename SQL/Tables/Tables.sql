-- Suppression des tables existantes
DROP TABLE IF EXISTS Desservir;
DROP TABLE IF EXISTS Implique;
DROP TABLE IF EXISTS Concourt;
DROP TABLE IF EXISTS Se_Deroule;
DROP TABLE IF EXISTS Participe_a;
DROP TABLE IF EXISTS Se_Produit;
DROP TABLE IF EXISTS Avoir;
DROP TABLE IF EXISTS Calendrier;
DROP TABLE IF EXISTS Athletes;
DROP TABLE IF EXISTS Resultats;
DROP TABLE IF EXISTS Epreuves;
DROP TABLE IF EXISTS Ceremonies;
DROP TABLE IF EXISTS Arrets;
DROP TABLE IF EXISTS Voyages;
DROP TABLE IF EXISTS Lignes;
DROP TABLE IF EXISTS Agences;
DROP TABLE IF EXISTS Sites;
DROP TABLE IF EXISTS Villes;
DROP TABLE IF EXISTS Pays;

-- ENTITES

-- CREATE TABLE Calendrier (
--     Date_Calendrier DATE PRIMARY KEY,
--     Duree_Calendrier TIME
-- ) ENGINE=InnoDB;

CREATE TABLE Agences (
    ID_Agences VARCHAR(25) PRIMARY KEY,
    Nom_Agences VARCHAR(50),
    Url_Agences VARCHAR(200),
    Timezone_Agences VARCHAR(25),
    Lang_Agences VARCHAR(25),
    Tel_Agences VARCHAR(25),
    Email_Agences VARCHAR(25),
    Tarifs_Url_Agences VARCHAR(200)
) ENGINE=InnoDB;

CREATE TABLE Lignes (
    ID_Lignes VARCHAR(25) PRIMARY KEY,
    Nom_Lignes VARCHAR(25),
    Nom_long_Lignes VARCHAR(50),
    Mode_Lignes VARCHAR(25),
    ID_Agences VARCHAR(25)
) ENGINE=InnoDB;

ALTER TABLE Lignes ADD CONSTRAINT FK_Lignes_ID_Agences FOREIGN KEY (ID_Agences) REFERENCES Agences (ID_Agences);

CREATE TABLE Voyages (
    ID_Voyages VARCHAR(100) PRIMARY KEY,
    ID_Lignes VARCHAR(25),
    Nom_Voyages VARCHAR(100),
    Accessible_Fauteuil_Voyages INT,
    Accessible_Velo_Voyages INT
) ENGINE=InnoDB;

ALTER TABLE Voyages ADD CONSTRAINT FK_Voyages_ID_Lignes FOREIGN KEY (ID_Lignes) REFERENCES Lignes (ID_Lignes);


CREATE TABLE Arrets (
    ID_Arrets VARCHAR(25) PRIMARY KEY,
    ID_Voyages VARCHAR(100),
    Nom_Arrets VARCHAR(50),
    Latitude_Arrets DECIMAL(20,8),
    Longitude_Arrets DECIMAL(20,8), 
    Accessible_Arrets VARCHAR(2),
    Heure_arrive_Arrets TIME,
    Heure_depart_Arrets TIME,
    Numero_Arrets INT
) ENGINE=InnoDB;

DROP TRIGGER IF EXISTS delete_agence_trigger;
DROP TRIGGER IF EXISTS delete_ligne_trigger;
DROP TRIGGER IF EXISTS delete_voyage_trigger;
CREATE TRIGGER delete_agence_trigger BEFORE DELETE ON Agences FOR EACH ROW UPDATE Lignes SET ID_Agences = NULL WHERE ID_Agences = OLD.ID_Agences;
CREATE TRIGGER delete_ligne_trigger BEFORE DELETE ON Lignes FOR EACH ROW UPDATE Voyages SET ID_Lignes = NULL WHERE ID_Lignes = OLD.ID_Lignes;
CREATE TRIGGER delete_voyage_trigger BEFORE DELETE ON Voyages FOR EACH ROW UPDATE Arrets SET ID_Voyages = NULL WHERE ID_Voyages = OLD.ID_Voyages;

-- ALTER TABLE Arrets ADD CONSTRAINT FK_Arrets_ID_Voyages FOREIGN KEY (ID_Voyages) REFERENCES Voyages (ID_Voyages);

CREATE TABLE Villes (
    Nom_Villes VARCHAR(100),
    Code_Postal_Villes VARCHAR(10),
    Population_Villes INT,
    PRIMARY KEY (Nom_Villes, Code_Postal_Villes)
) ENGINE=InnoDB; 

CREATE TABLE Sites (
    Latitude_Sites DECIMAL(20,8),
    Longitude_Sites DECIMAL(20,8),
    Nom_Sites VARCHAR(100),
    Date_de_construction_Sites DATE,
    Capacite_d_acceuil_Sites INT,
    Accessibilite_Sites VARCHAR(100),
    Nom_Villes VARCHAR(100),
    PRIMARY KEY (Latitude_Sites, Longitude_Sites),
    FOREIGN KEY (Nom_Villes) REFERENCES Villes(Nom_Villes)
) ENGINE=InnoDB;

DROP TRIGGER IF EXISTS delete_villes_trigger;
CREATE TRIGGER delete_villes_trigger BEFORE DELETE ON Villes FOR EACH ROW UPDATE Sites SET Nom_Villes = NULL WHERE Nom_Villes = OLD.Nom_Villes;


-- CREATE TABLE Pays (
--     Nom_Pays VARCHAR(100) PRIMARY KEY,
--     Drapeau_Pays VARCHAR(100)
-- ) ENGINE=InnoDB;

CREATE TABLE Pays (
    ID VARCHAR(3) PRIMARY KEY,
    Drapeau VARCHAR(200),
    Nom_Francais VARCHAR(100),
    Nom_Anglais VARCHAR(200)
) ENGINE=InnoDB;


CREATE TABLE Resultats (
    ID_Resultats INT PRIMARY KEY,
    Performance_Resultats VARCHAR(100),
    Est_record_Resultats BOOLEAN,
    Medaille_Resultats VARCHAR(100)
) ENGINE=InnoDB;

CREATE TABLE Athletes (
    ID_Athletes INT AUTO_INCREMENT PRIMARY KEY,
    Image_url_Athletes VARCHAR(200),
    Profil_url_Athletes VARCHAR(200),
    Nom_Athletes VARCHAR(100),
    ID_Epreuves VARCHAR(20),
    ID VARCHAR(3),
    Date_naissance_Athletes DATE,
    Lieu_naissance_Athletes VARCHAR(100),
    Taille_Athletes VARCHAR(10)
) ENGINE=InnoDB;

DROP TRIGGER IF EXISTS delete_Pays_trigger;
CREATE TRIGGER delete_Pays_trigger BEFORE DELETE ON Pays FOR EACH ROW UPDATE Athletes SET ID = NULL WHERE ID = OLD.ID;


CREATE TABLE Epreuves (
    ID_Epreuves VARCHAR(20) PRIMARY KEY,
    Nom_Epreuves VARCHAR(100),
    Name_Epreuves VARCHAR(100),
    Categorie_Epreuves BOOLEAN,
    Type_Epreuves BOOLEAN,
    Logo_Epreuves VARCHAR(100)
) ENGINE=InnoDB;

CREATE TABLE Ceremonies (
    ID_Ceremonies VARCHAR(25) PRIMARY KEY,
    Nom_Ceremonies VARCHAR(100)
) ENGINE=InnoDB;


-- ASSOCIATIONS

CREATE TABLE Desservir (
    ID_Transports INT AUTO_INCREMENT NOT NULL,
    Latitude_Sites DECIMAL(20,8) NOT NULL,
    PRIMARY KEY (ID_Transports, Latitude_Sites)
) ENGINE=InnoDB;

CREATE TABLE Implique (
    ID_Epreuves VARCHAR(20) NOT NULL,
    ID_Resultats INT NOT NULL,
    PRIMARY KEY (ID_Epreuves, ID_Resultats)
) ENGINE=InnoDB;

CREATE TABLE Concourt (
    ID_Epreuves VARCHAR(20) NOT NULL,
    ID_Athletes INT NOT NULL,
    PRIMARY KEY (ID_Epreuves, ID_Athletes)
) ENGINE=InnoDB;

CREATE TABLE Se_Produit (
    ID_Ceremonies VARCHAR(25) NOT NULL,
    Longitude_Sites DECIMAL(20,8) NOT NULL,
    Latitude_Sites DECIMAL(20,8) NOT NULL,
    Date_Debut DATE NOT NULL,
    Date_Fin DATE NOT NULL,
    PRIMARY KEY (ID_Ceremonies, Latitude_Sites, Longitude_Sites, Date_Debut, Date_Fin)
) ENGINE=InnoDB;

CREATE TABLE Se_Deroule (
    ID_Epreuves VARCHAR(25) NOT NULL,
    Longitude_Sites DECIMAL(20,8) NOT NULL,
    Latitude_Sites DECIMAL(20,8) NOT NULL,
    Date_Debut DATE NOT NULL,
    Date_Fin DATE NOT NULL,
    PRIMARY KEY (ID_Epreuves, Latitude_Sites, Longitude_Sites, Date_Debut, Date_Fin)
) ENGINE=InnoDB;

CREATE TABLE Participe_a (
    ID_Ceremonies VARCHAR(25) NOT NULL,
    ID_Athletes INT NOT NULL,
    PRIMARY KEY (ID_Ceremonies, ID_Athletes)
) ENGINE=InnoDB;


CREATE TABLE Avoir (
    ID_Resultats INT AUTO_INCREMENT NOT NULL,
    ID_Athletes INT NOT NULL,
    PRIMARY KEY (ID_Resultats, ID_Athletes)
) ENGINE=InnoDB;


-- CONTRAINTES ASSOCIATIONS

-- ALTER TABLE Desservir ADD CONSTRAINT FK_Desservir_ID_Transports FOREIGN KEY (ID_Transports) REFERENCES Transports (ID_Transports);
-- ALTER TABLE Desservir ADD CONSTRAINT FK_Desservir_Latitude_Sites FOREIGN KEY (Latitude_Sites) REFERENCES Sites (Latitude_Sites);
ALTER TABLE Implique ADD CONSTRAINT FK_Implique_ID_Epreuves FOREIGN KEY (ID_Epreuves) REFERENCES Epreuves (ID_Epreuves);
ALTER TABLE Implique ADD CONSTRAINT FK_Implique_ID_Resultats FOREIGN KEY (ID_Resultats) REFERENCES Resultats (ID_Resultats);
ALTER TABLE Concourt ADD CONSTRAINT FK_Concourt_ID_Epreuves FOREIGN KEY (ID_Epreuves) REFERENCES Epreuves (ID_Epreuves);
ALTER TABLE Concourt ADD CONSTRAINT FK_Concourt_ID_Athletes FOREIGN KEY (ID_Athletes) REFERENCES Athletes (ID_Athletes);
ALTER TABLE Se_Produit ADD CONSTRAINT FK_Se_Produit_ID_Ceremonies FOREIGN KEY (ID_Ceremonies) REFERENCES Ceremonies (ID_Ceremonies);
ALTER TABLE Se_Produit ADD CONSTRAINT FK_Se_Produit_Latitude_Sites FOREIGN KEY (Latitude_Sites) REFERENCES Sites (Latitude_Sites);
ALTER TABLE Se_Deroule ADD CONSTRAINT FK_Se_Deroule_ID_Epreuves FOREIGN KEY (ID_Epreuves) REFERENCES Epreuves (ID_Epreuves);
ALTER TABLE Se_Deroule ADD CONSTRAINT FK_Se_Deroule_Latitude_Sites FOREIGN KEY (Latitude_Sites) REFERENCES Sites (Latitude_Sites);
ALTER TABLE Participe_a ADD CONSTRAINT FK_Participe_a_ID_Ceremonies FOREIGN KEY (ID_Ceremonies) REFERENCES Ceremonies (ID_Ceremonies);
ALTER TABLE Participe_a ADD CONSTRAINT FK_Participe_a_ID_Athletes FOREIGN KEY (ID_Athletes) REFERENCES Athletes (ID_Athletes);
ALTER TABLE Avoir ADD CONSTRAINT FK_Avoir_ID_Resultats FOREIGN KEY (ID_Resultats) REFERENCES Resultats (ID_Resultats);
ALTER TABLE Avoir ADD CONSTRAINT FK_Avoir_ID_Athletes FOREIGN KEY (ID_Athletes) REFERENCES Athletes (ID_Athletes);


DROP TRIGGER IF EXISTS delete_epreuve_trigger;
DROP TRIGGER IF EXISTS delete_epreuve_trigger_2;
DROP TRIGGER IF EXISTS delete_epreuve_trigger_3;
CREATE TRIGGER delete_epreuve_trigger BEFORE DELETE ON Epreuves FOR EACH ROW DELETE FROM Implique WHERE ID_Epreuves = OLD.ID_Epreuves;
CREATE TRIGGER delete_epreuve_trigger_2 BEFORE DELETE ON Epreuves FOR EACH ROW DELETE FROM Se_Deroule WHERE ID_Epreuves = OLD.ID_Epreuves;
CREATE TRIGGER delete_epreuve_trigger_3 BEFORE DELETE ON Epreuves FOR EACH ROW DELETE FROM Concourt WHERE ID_Epreuves = OLD.ID_Epreuves;

DROP TRIGGER IF EXISTS delete_resultat_trigger;
DROP TRIGGER IF EXISTS delete_resultat_trigger2;
CREATE TRIGGER delete_resultat_trigger BEFORE DELETE ON Resultats FOR EACH ROW DELETE FROM Avoir WHERE ID_Resultats = OLD.ID_Resultats;
CREATE TRIGGER delete_resultat_trigger2 BEFORE DELETE ON Resultats FOR EACH ROW DELETE FROM Implique WHERE ID_Resultats = OLD.ID_Resultats;

DROP TRIGGER IF EXISTS delete_ceremonie_trigger;
DROP TRIGGER IF EXISTS delete_ceremonie_trigger_2;
CREATE TRIGGER delete_ceremonie_trigger BEFORE DELETE ON Ceremonies FOR EACH ROW DELETE FROM Se_Produit WHERE ID_Ceremonies = OLD.ID_Ceremonies;
CREATE TRIGGER delete_ceremonie_trigger_2 BEFORE DELETE ON Ceremonies FOR EACH ROW DELETE FROM Participe_a WHERE ID_Ceremonies = OLD.ID_Ceremonies;

DROP TRIGGER IF EXISTS delete_site_trigger;
DROP TRIGGER IF EXISTS delete_site_trigger_2;
CREATE TRIGGER delete_site_trigger BEFORE DELETE ON Sites FOR EACH ROW DELETE FROM Se_Produit WHERE Latitude_Sites = OLD.Latitude_Sites AND Longitude_Sites = OLD.Longitude_Sites;
CREATE TRIGGER delete_site_trigger_2 BEFORE DELETE ON Sites FOR EACH ROW DELETE FROM Se_Deroule WHERE Latitude_Sites = OLD.Latitude_Sites AND Longitude_Sites = OLD.Longitude_Sites;

DROP TRIGGER IF EXISTS delete_athlete_trigger;
DROP TRIGGER IF EXISTS delete_athlete_trigger_2;
DROP TRIGGER IF EXISTS delete_athlete_trigger_3;
CREATE TRIGGER delete_athlete_trigger BEFORE DELETE ON Athletes FOR EACH ROW DELETE FROM Concourt WHERE ID_Athletes = OLD.ID_Athletes;
CREATE TRIGGER delete_athlete_trigger_2 BEFORE DELETE ON Athletes FOR EACH ROW DELETE FROM Participe_a WHERE ID_Athletes = OLD.ID_Athletes;
CREATE TRIGGER delete_athlete_trigger_3 BEFORE DELETE ON Athletes FOR EACH ROW DELETE FROM Avoir WHERE ID_Athletes = OLD.ID_Athletes;