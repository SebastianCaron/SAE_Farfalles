import sys
import ftplib
import os
import time


FTP_HOST = 'ftp.cluster031.hosting.ovh.net'
FTP_TARGET_DIR = '/www/projets/Olympics'

EXCEPTION_FILE = './libs/config.php'


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
                ftp.sendcmd('MDTM ' + ftp_path)
                # Si le fichier existe, obtenir la date de dernière modification
                server_mtime = ftp.sendcmd('MDTM ' + ftp_path)[4:].strip()
                server_mtime = time.strptime(server_mtime, '%Y%m%d%H%M%S')
                server_mtime = time.mktime(server_mtime)
                local_mtime = os.path.getmtime(local_path)

                # Si le fichier local est plus récent, télécharger
                if local_mtime > server_mtime:
                    with open(local_path, 'rb') as f:
                        ftp.storbinary('STOR ' + ftp_path, f)
            except ftplib.error_perm:
                # Si le fichier n'existe pas sur le serveur, télécharger
                with open(local_path, 'rb') as f:
                    ftp.storbinary('STOR ' + ftp_path, f)

    ftp.quit()

if __name__ == "__main__":
    username = sys.argv[1]
    password = sys.argv[2]
    
    ftp_upload(os.getcwd() + "/Website/", username, password)
    
