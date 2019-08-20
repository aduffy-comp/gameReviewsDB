Important Note
==============
This database was originally created under the XAMPP development environment. It is compatible with MySQL database systems, and may not work correctly under other software such as SQL Server.

Using These Files
=================
Copy the gameReviews directory to your XAMPP/htdocs folder. Inside the gameReviews folder, open connection.php in a text editor and change "user" and "password" to your database's username and password for a localhost connection.

index.php - Inside the gameReviews folder.

Open phpMyAdmin and import the "modReviews2.sql" file. Ensure that a database with this name does not exist and that a database is not open during the import procedure (click the phpMyAdmin logo before importing).

Existing Accounts
=================
The database comes with several existing user accounts with which to post reviews. Below is a list of these:

TheBigBoss / TheBigB0ss
Is set as admin, but this flag is not used yet.

s / s
IsleOfRatchet / LombaxLover926
Are set as banned, you should not be able to post from these accounts.

TheRealBoss / TheRealBoss
Standard user account with no flags.

There are 3 additional standard accounts, but I forgot their passwords.

Known Issues
============
- Primary headings are too large for small screen widths of less than 420px (before device scaling). This affects most phones in portrait mode.
- Element size does not change instantly when device is rotated: navigating to another page fixes it.
- Profile images are not currently used, nor is the users.userIsAdmin flag.
- No method exists to reset forgotten passwords.
- Mod / game names with certain special characters (including apostrophies) have issues with their game / mod information pages being inaccessible.