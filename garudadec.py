import glob
from os import popen

files = ['.' + f for f in glob.glob('./application/**/*.php')]
files = list(set(
    files + ['.' + f for f in glob.glob('./installer/**/*.php')] + ['.' + f for f in glob.glob('./system/**/*.php')]
))

for file in files:
    code = popen(f"cd PHPDeobfuscator && php index.php -f {file}").read()
    f = open(file[1:], "w+")

    f.write(code)
    f.close()
    print("successfuly dec " + file)