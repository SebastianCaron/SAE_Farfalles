import sys
import ftplib
import os
import time


FTP_HOST = 'ftp.cluster031.hosting.ovh.net'
FTP_TARGET_DIR = '/www/projets/Olympics'

EXCEPTION_FILE = './libs/config.php'

def ftp_upload(local_dir, ftp, local_path=''):
    for item in os.listdir(local_dir):
        local_item = os.path.join(local_dir, item)
        if os.path.isfile(local_item):
            remote_path = os.path.join(FTP_TARGET_DIR, local_path, item)
            try:
                ftp.cwd(remote_path)
            except ftplib.error_perm:
                ftp.mkd(remote_path)
                ftp.cwd(remote_path)
            with open(local_item, 'rb') as file:
                if item != EXCEPTION_FILE:
                    try:
                        ftp.storbinary('STOR ' + item, file)
                    except Exception as e:
                        print("Error:", e)
                        continue
        elif os.path.isdir(local_item):
            ftp_upload(local_item, ftp, os.path.join(local_path, item))

if __name__ == "__main__":
    username = sys.argv[1]
    password = sys.argv[2]
    
    ftp_upload(os.getcwd() + "/Website/", username, password)
    
