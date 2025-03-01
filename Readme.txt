#Ecoride - Déploiement Local

Ce guide explique comment installer et exécuter le projet en local.

## Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- [Node.js](version recommandée : 18+)
- [XAMPP](serveur Apache avec PHP et MySQL) 
- [Composer]
- [Git]
- [MongoDB] (uniquement pour les reviews des utilisateurs, le reste est gérer par MySQL)

## 1️⃣ Cloner le projet

Ouvrez un terminal et exécutez :

```sh
# Cloner le projet
git clone https://github.com/Saifzgh/ecoride-projet

# Aller dans le dossier
cd ecoride


## 2️⃣ Configuration du Backend (PHP & MySQL)

# 📌 Créer la base de données MySQL

1. Démarrer XAMPP et s'assurer que Apache et MySQL sont bien activés.
2. Ouvrir phpMyAdmin (http://localhost/phpmyadmin/) ou "Admin" sur le panneau de contrôle XAMPP sur la ligne mySQL
3. Créer une base de données "ecoride".
4. Clicker en haut sur <importer> et insérer le fichier "ecoride.sql" fournis pour créer les tables 


### 📌 Installer composer (si ce n'est pas déjà fait !)



Se placer dans le dossier "BACKEND" et exécuter :

composer install



### 📌 Configurer la base de données MySQL

Modifier le fichier "config/database.php" avec vos paramètres MySQL :


$host = "127.0.0.1"; (ou localhost)
$dbname = "ecoride";
$username = "root"; (identifiant de votre base sql, par défaut root)
$password = ""; (mot de passe de votre base sql, par défaut vide)


### 📌 Lancer le serveur PHP

Démarrer le serveur PHP pour l'API avec votre terminal :


php -S localhost:8000 -t public


L'API est maintenant accessible à l'adresse : http://localhost:8000

## 📦 3️⃣ Configuration du Frontend (React)

### 📌 Installer les dépendances ( FRONT )

Ici les dépendances sont déjà installer, mais si ce n'est pas le cas alors :

Se placer dans le dossier "FRONTEND" et installer les packages avec la commande suivante


npm install

* vérifier si node.js est installé grâce à la commande "node -v" et si npm est installé
  avec la commande "npm -v". Si vous ne posséder pas de package.json, crée-le avec "npm init -y" 


### 📌 Lancer l'application React

Démarrer l'application avec le terminal (vérifier d'être bien dans le dossier FRONTEND) :


npm run dev


L'application sera accessible à : http://localhost:5173. (Attention il peux varier selon votre port)

##  4️⃣ Configuration MongoDB (Avis des Passagers)

1. Démarrer MongoDB (si ce n'est pas fait) :


mongod --dbpath="C:\path\vers\mongodb\data"


2. Créer la base de données et la collection :


use ecoride_reviews


L'API gérera les avis automatiquement.

## 5️⃣ Tester l'Application 🚀

Vous avez déjà 4 utilisateurs inscrit dans la bdd si vous le souhaitez, vous pouvez vous connecté avec les infos suivantes:

- ADMIN : mail = admin@example.com   /   mot de passe = password
- EMPLOYE : mail = employe@example.com   /   mot de passe = password
- CHAUFFEUR : mail = chauffeur@example.com   /   mot de passe = password
- CHAUFFEUR-PASSAGER : mail = both@example.com   /   mot de passe = password


Sinon suivez les étapes suivantes :


- rendez vous dans l'éspace utilisateur et inscriver vous pour créer un compte.
- Connecter vous.
- Dans l'éspace utilisateur vous pouvez vous définier comme passager, chauffeur ou les deux, créer un covoiturage       renseigner votre véhicule et voire vos réservations.
- rendez vous dans la pager covoiturages grâce à la navbar et chercher un covoiturage, par défault j'ai déjà créer deux covoiturages pour les tests avec ces renseignements:
  ville départ (paris) / ville d'arrivée (marseille) / date départ (31/12/2025)
  ville départ (lyon) / ville d'arrivée (paris) / date départ (31/12/2025)


- Vous pouvez soit vous participer à ces covoiturages soit créer les votre (en sélectionnant bien chauffeur ou chauffeur-passager comme rôle)

## 🛠 Dépannage

❌ "CORS Policy Error in React" :
Ajoutez cette ligne dans `backend/public/index.php` : header("Access-Control-Allow-Origin: *");

❌ "ERR_CONNECTION_REFUSED" :
C'est que le serveur php n'est pas démarrer, dans le dossier BACKEND taper dans le terminal < php -S localhost:8000 -t public >.

## Il reste plusieurs table qui ne sont pas encore développer comme les transactions de crédit et autres que je terminerai au fur et à mesure



