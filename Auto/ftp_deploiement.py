import sys
import ftplib
import os
import time

import hashlib


FTP_HOST = 'ftp.cluster031.hosting.ovh.net'
FTP_TARGET_DIR = '/www/projets/Olympics'

EXCEPTION_FILE = './libs/config.php'

def get_file_hash(file_path):
    """
    Calcule le hachage MD5 du contenu du fichier.
    """
    hasher = hashlib.md5()
    with open(file_path, 'rb') as f:
        for chunk in iter(lambda: f.read(4096), b''):
            hasher.update(chunk)
    return hasher.hexdigest()

def ftp_upload(local_dir, ftp_username, ftp_password):
    ftp = ftplib.FTP(FTP_HOST, ftp_username, ftp_password)
    ftp.cwd(FTP_TARGET_DIR)

    for root, dirs, files in os.walk(local_dir):
        # Répertoire sur le serveur correspondant à celui sur le système local
        ftp_root = os.path.join(FTP_TARGET_DIR, os.path.relpath(root, local_dir))

        # Créer des répertoires manquants sur le serveur
        try:
            ftp.mkd(ftp_root)
        except ftplib.error_perm:
            pass

        ftp.cwd(ftp_root)

        for file in files:
            local_path = os.path.join(root, file)
            ftp_path = os.path.join(ftp_root, file)

            # Vérifier si le fichier existe sur le serveur
            try:
                # Calculer le hachage du fichier local
                local_hash = get_file_hash(local_path)

                # Télécharger le fichier si son hachage est différent ou s'il n'existe pas sur le serveur
                try:
                    # Télécharger le fichier pour obtenir son hachage sur le serveur
                    with open(local_path, 'rb') as f:
                        ftp.storbinary('STOR ' + ftp_path, f)

                    # Calculer le hachage du fichier téléchargé sur le serveur
                    ftp_hash = hashlib.md5(ftp.retrbinary('RETR ' + ftp_path, f.read)).hexdigest()

                    # Comparer les hachages
                    if local_hash != ftp_hash:
                        # Télécharger le fichier car les hachages sont différents
                        with open(local_path, 'rb') as f:
                            ftp.storbinary('STOR ' + ftp_path, f)
                except ftplib.error_perm:
                    # Le fichier n'existe pas sur le serveur, donc le télécharger
                    with open(local_path, 'rb') as f:
                        ftp.storbinary('STOR ' + ftp_path, f)
            except ftplib.error_perm:
                pass

    ftp.quit()
if __name__ == "__main__":
    username = sys.argv[1]
    password = sys.argv[2]
    
    ftp_upload(os.getcwd() + "/Website/", username, password)
    
