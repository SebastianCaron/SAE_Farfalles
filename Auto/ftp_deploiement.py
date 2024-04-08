import sys
import ftplib
import os
import time


FTP_HOST = 'ftp.cluster031.hosting.ovh.net'
FTP_TARGET_DIR = '/www/projets/Olympics'

EXCEPTION_FILE = './libs/config.php'

def ftp_upload(local_path, username, password):
    try:
        ftp = ftplib.FTP(FTP_HOST, username, password)
        ftp.cwd(FTP_TARGET_DIR)
        
        for root, dirs, files in os.walk(local_path):
            for d in dirs:
                ftp.mkd(os.path.join(root, d).replace(local_path, '').lstrip('/'))

            for f in files:
                local_file = os.path.join(root, f)
                remote_file = os.path.join(root, f).replace(local_path, '').lstrip('/')
                local_mtime = os.path.getmtime(local_file)
                
                try:
                    remote_mtime = ftp.sendcmd('MDTM ' + remote_file)
                    remote_mtime = time.strptime(remote_mtime[4:], '%Y%m%d%H%M%S')
                    remote_mtime = time.mktime(remote_mtime)
                except ftplib.error_perm:
                    remote_mtime = 0

                if local_mtime > remote_mtime:
                    with open(local_file, 'rb') as file:
                        ftp.storbinary('STOR ' + remote_file, file)

        ftp.quit()
        print("Upload successful!")
    except ftplib.all_errors as e:
        print("FTP error:", e)

if __name__ == "__main__":
    username = sys.argv[1]
    password = sys.argv[2]
    
    ftp_upload(os.getcwd() + "/Website/", username, password)
    
