import sys
import ftplib
import os
import time


FTP_HOST = 'ftp.cluster031.hosting.ovh.net'
FTP_TARGET_DIR = '/www/projets/Olympics'

EXCEPTION_FILE = './libs/config.php'

def ftp_upload(local_dir, username, password):
    try:
        ftp = ftplib.FTP(FTP_HOST, username, password)
        ftp.cwd(FTP_TARGET_DIR)
        upload_dir_contents(ftp, local_dir)
        ftp.quit()
    except ftplib.all_errors as e:
        print("FTP error:", e)

def upload_dir_contents(ftp, local_dir):
    for item in os.listdir(local_dir):
        local_path = os.path.join(local_dir, item)
        remote_path = os.path.join(FTP_TARGET_DIR, item)
        if os.path.isfile(local_path):
            if item != EXCEPTION_FILE.split('/')[-1]:
                if should_upload(local_path, remote_path, ftp):
                    upload_file(ftp, local_path, remote_path)
        elif os.path.isdir(local_path):
            try:
                ftp.mkd(remote_path)
            except ftplib.error_perm as e:
                if "550" not in str(e):
                    print("Error creating remote directory:", e)
            upload_dir_contents(ftp, local_path)

def should_upload(local_file, remote_file, ftp):
    try:
        local_mod_time = os.path.getmtime(local_file)
        try:
            remote_mod_time = ftp.sendcmd('MDTM ' + remote_file)
            remote_mod_time = time.mktime(time.strptime(remote_mod_time[4:], '%Y%m%d%H%M%S'))
            return local_mod_time > remote_mod_time
        except ftplib.error_perm as e:
            if "550" in str(e):
                return True
            else:
                print("Error checking remote file:", e)
                return False
    except OSError as e:
        print("Error accessing local file:", e)
        return False

def upload_file(ftp, local_file, remote_file):
    try:
        with open(local_file, 'rb') as f:
            ftp.storbinary('STOR ' + remote_file, f)
        print(f"Uploaded {local_file} to {remote_file}")
    except ftplib.all_errors as e:
        print("FTP upload error:", e)


if __name__ == "__main__":
    username = sys.argv[1]
    password = sys.argv[2]
    
    ftp_upload(os.getcwd() + "/Website/", username, password)
    
