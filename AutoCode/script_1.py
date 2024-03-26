import os

folders = ['A', 'B', 'C']

for folder in folders:
    os.makedirs(folder, exist_ok=True)
