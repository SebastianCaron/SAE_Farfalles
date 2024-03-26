import os

folders = ['A', 'B', 'C', 'D']

for folder in folders:
    os.makedirs(folder, exist_ok=True)
