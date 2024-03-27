import sys
import ftplib
import os


FTP_HOST = 'ftp.cluster031.hosting.ovh.net'
FTP_TARGET_DIR = '/www/projets/Olympics'

def ftp_upload(local_dir, username, password):
    try:
        ftp = ftplib.FTP(FTP_HOST, username, password)
        ftp.cwd(FTP_TARGET_DIR)
        for file in os.listdir(local_dir):
            if os.path.isfile(os.path.join(local_dir, file)):
                with open(os.path.join(local_dir, file), 'rb') as f:
                    ftp.storbinary('STOR ' + file, f)
        ftp.quit()
        print("Fichiers OK !")
    except Exception as e:
        print("Erreur : ", e)




if __name__ == "__main__":
    username = sys.argv[1]
    password = sys.argv[2]
    
    ftp_upload(os.getcwd() + "/Website/", username, password)
    
