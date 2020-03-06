@echo off

set dbUser=root
set dbPassword=""
set banco="sgaf_agape"
set backupDir="C:\Users\User\Desktop\backup\mysql"
set mysqldump="C:\xampp\mysql\bin\mysqldump.exe"
set mysqlDataDir="C:\xampp\mysql\data"
set zip="C:\Program Files\7-Zip\7zG.exe"

%mysqldump% --host="localhost" --user=%dbUser% --password=%dbPassword% --single-transaction --add-drop-table  %banco%>%backupDir%\%banco%_%date:~6,4%-%date:~3,2%-%date:~0,2%.sql

%zip% a -tgzip %backupDir%\%banco%_%date:~6,4%-%date:~3,2%-%date:~0,2%.sql.gz %backupDir%\%banco%_%date:~6,4%-%date:~3,2%-%date:~0,2%.sql
del %backupDir%\%banco%_%date:~6,4%-%date:~3,2%-%date:~0,2%.sql

