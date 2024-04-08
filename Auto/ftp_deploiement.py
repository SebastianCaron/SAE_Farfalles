import sys
import ftplib
import os


FTP_HOST = 'ftp.cluster031.hosting.ovh.net'
FTP_TARGET_DIR = '/www/projets/Olympics'

EXCEPTION_FILE = './libs/config.php'

def upload_files(ftp, local_dir, remote_dir):
    for file in os.listdir(local_dir):
        local_path = os.path.join(local_dir, file)
        remote_path = os.path.join(remote_dir, file)
        if os.path.isfile(local_path):
            # Comparer la date de modification des fichiers pour déterminer s'ils sont différents
            try:
                local_mtime = os.path.getmtime(local_path)
                remote_mtime = ftp.sendcmd('MDTM ' + remote_path)[4:].strip()
                remote_mtime = ftp.voidcmd('MDTM ' + remote_path)[4:].strip()
                remote_mtime = ftp.sendcmd('MDTM ' + remote_path)[4:].strip()
                if str(local_mtime) == remote_mtime:
                    print(f"{local_path} déjà à jour, sauté.")
                    continue
            except:
                pass

            with open(local_path, 'rb') as f:
                ftp.storbinary('STOR ' + remote_path, f)
        elif os.path.isdir(local_path):
            try:
                ftp.mkd(remote_path)
            except ftplib.error_perm:
                pass  # Répertoire déjà existant
            upload_files(ftp, local_path, remote_path)

def ftp_upload(local_dir, username, password):
    try:
        ftp = ftplib.FTP(FTP_HOST, username, password)
        ftp.cwd(FTP_TARGET_DIR)

        # Supprimer tous les fichiers sauf EXCEPTION_FILE
        files_to_keep = [EXCEPTION_FILE]
        ftp_files = ftp.nlst()
        for ftp_file in ftp_files:
            if ftp_file != EXCEPTION_FILE:
                ftp.delete(ftp_file)

        # Charger les nouveaux fichiers
        upload_files(ftp, local_dir, '/')
        
        ftp.quit()
        print("Fichiers OK !")
    except Exception as e:
        print("Erreur : ", e)


if __name__ == "__main__":
    username = sys.argv[1]
    password = sys.argv[2]
    
    ftp_upload(os.getcwd() + "/Website/", username, password)
    
