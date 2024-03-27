/* Tables.sql */
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
    ID_Agence VARCHAR(25)
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
    Latitute_Arrets DECIMAL(20,6),
    Longitude_Arrets DECIMAL(20,6), 
    Accessible_Arrets VARCHAR(2),
    Heure_arrive_Arrets TIME,
    Heure_depart_Arrets TIME,
    Numero_Arrets INT
) ENGINE=InnoDB;

ALTER TABLE Arrets ADD CONSTRAINT FK_Arrets_ID_Voyages FOREIGN KEY (ID_Voyages) REFERENCES Voyages (ID_Voyages);

 
CREATE TABLE Sites (
    Latitude_Sites DECIMAL(20,6) DEFAULT NULL,
    Longitude_Sites DECIMAL(20,6) DEFAULT NULL,
    Nom_Sites VARCHAR(100),
    Date_de_construction_Sites DATE,
    Capacite_d_acceuil_Sites INT,
    Accessibilite_Sites VARCHAR(100),
    Nom_Villes VARCHAR(100),
    PRIMARY KEY (Latitude_Sites, Longitude_Sites),
    FOREIGN KEY (Nom_Villes) REFERENCES Villes(Nom_Villes)
) ENGINE=InnoDB;

CREATE TABLE Villes (
    Nom_Villes VARCHAR(100),
    Code_Postal_Villes VARCHAR(10),
    Population_Villes INT,
    PRIMARY KEY (Nom_Villes, Code_Postal_Villes)
) ENGINE=InnoDB;

-- CREATE TABLE Pays (
--     Nom_Pays VARCHAR(100) PRIMARY KEY,
--     Drapeau_Pays VARCHAR(100)
-- ) ENGINE=InnoDB;

CREATE TABLE Pays (
    ID INT AUTO_INCREMENT PRIMARY KEY,
    Drapeau VARCHAR(200),
    Nom_Français VARCHAR(100),
    Nom_Anglais VARCHAR(200)
) ENGINE=InnoDB;


CREATE TABLE Resultats (
    ID_Resultats INT PRIMARY KEY,
    Performance_Resultats VARCHAR(100),
    Est_record_Resultats BOOLEAN,
    Medaille_Resultats VARCHAR(100)
) ENGINE=InnoDB;

CREATE TABLE Athletes (
    ID_Athletes INT PRIMARY KEY,
    Image_url_Athletes VARCHAR(200),
    Profil_url_Athletes VARCHAR(200),
    Nom_Athletes VARCHAR(100),
    ID_Epreuves INT,
    ID INT,
    Date_naissance_Athletes DATE,
    Lieu_naissance_Athletes VARCHAR(100),
    Taille_Athletes VARCHAR(10)
) ENGINE=InnoDB;

CREATE TABLE Epreuves (
    ID_Epreuves INT PRIMARY KEY,
    Nom_Epreuves VARCHAR(100),
    Categorie_Epreuves VARCHAR(100),
    Logo_Epreuves VARCHAR(100),
    Latitude_Sites DECIMAL(20,6),
    Longitude_Sites DECIMAL(20,6),
    FOREIGN KEY (Latitude_Sites) REFERENCES Sites(Latitude_Sites)
) ENGINE=InnoDB;

CREATE TABLE Ceremonies (
    ID_Ceremonies INT PRIMARY KEY,
    Nom_Ceremonies VARCHAR(100)
) ENGINE=InnoDB;


-- ASSOCIATIONS

CREATE TABLE Desservir (
    ID_Transports INT AUTO_INCREMENT NOT NULL,
    Latitude_Sites DECIMAL(20,6) NOT NULL,
    PRIMARY KEY (ID_Transports, Latitude_Sites)
) ENGINE=InnoDB;

CREATE TABLE Implique (
    ID_Epreuves INT AUTO_INCREMENT NOT NULL,
    ID_Resultats INT NOT NULL,
    PRIMARY KEY (ID_Epreuves, ID_Resultats)
) ENGINE=InnoDB;

CREATE TABLE Concourt (
    ID_Epreuves INT AUTO_INCREMENT NOT NULL,
    ID_Personnes INT NOT NULL,
    PRIMARY KEY (ID_Epreuves, ID_Personnes)
) ENGINE=InnoDB;

CREATE TABLE Se_Deroule (
    ID_Ceremonies INT AUTO_INCREMENT NOT NULL,
    Latitude_Sites DECIMAL(20,6) NOT NULL,
    PRIMARY KEY (ID_Ceremonies, Latitude_Sites)
) ENGINE=InnoDB;

CREATE TABLE Participe_a (
    ID_Ceremonies INT AUTO_INCREMENT NOT NULL,
    ID_Personnes INT NOT NULL,
    PRIMARY KEY (ID_Ceremonies, ID_Personnes)
) ENGINE=InnoDB;


CREATE TABLE Avoir (
    ID_Resultats INT AUTO_INCREMENT NOT NULL,
    ID_Personnes INT NOT NULL,
    PRIMARY KEY (ID_Resultats, ID_Personnes)
) ENGINE=InnoDB;


-- CONTRAINTES ASSOCIATIONS

ALTER TABLE Desservir ADD CONSTRAINT FK_Desservir_ID_Transports FOREIGN KEY (ID_Transports) REFERENCES Transports (ID_Transports);
ALTER TABLE Desservir ADD CONSTRAINT FK_Desservir_Latitude_Sites FOREIGN KEY (Latitude_Sites) REFERENCES Sites (Latitude_Sites);
ALTER TABLE Implique ADD CONSTRAINT FK_Implique_ID_Epreuves FOREIGN KEY (ID_Epreuves) REFERENCES Epreuves (ID_Epreuves);
ALTER TABLE Implique ADD CONSTRAINT FK_Implique_ID_Resultats FOREIGN KEY (ID_Resultats) REFERENCES Resultats (ID_Resultats);
ALTER TABLE Concourt ADD CONSTRAINT FK_Concourt_ID_Epreuves FOREIGN KEY (ID_Epreuves) REFERENCES Epreuves (ID_Epreuves);
ALTER TABLE Concourt ADD CONSTRAINT FK_Concourt_ID_Personnes FOREIGN KEY (ID_Personnes) REFERENCES Personnes (ID_Personnes);
ALTER TABLE Se_Deroule ADD CONSTRAINT FK_Se_Deroule_ID_Ceremonies FOREIGN KEY (ID_Ceremonies) REFERENCES Ceremonies (ID_Ceremonies);
ALTER TABLE Se_Deroule ADD CONSTRAINT FK_Se_Deroule_Latitude_Sites FOREIGN KEY (Latitude_Sites) REFERENCES Sites (Latitude_Sites);
ALTER TABLE Participe_à ADD CONSTRAINT FK_Participe_à_ID_Ceremonies FOREIGN KEY (ID_Ceremonies) REFERENCES Ceremonies (ID_Ceremonies);
ALTER TABLE Participe_à ADD CONSTRAINT FK_Participe_à_ID_Personnes FOREIGN KEY (ID_Personnes) REFERENCES Personnes (ID_Personnes);
ALTER TABLE Avoir ADD CONSTRAINT FK_Avoir_ID_Resultats FOREIGN KEY (ID_Resultats) REFERENCES Resultats (ID_Resultats);
ALTER TABLE Avoir ADD CONSTRAINT FK_Avoir_ID_Personnes FOREIGN KEY (ID_Personnes) REFERENCES Personnes (ID_Personnes);

