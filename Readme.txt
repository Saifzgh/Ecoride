# 🚗 EcoRide - Déploiement Local

Bienvenue dans **EcoRide**, l'application de covoiturage écologique ! 🌱 Ce guide explique comment installer et exécuter le projet en local.

## 📌 Prérequis

Avant de commencer, assurez-vous d'avoir installé les éléments suivants :

- [Node.js](https://nodejs.org/) (version recommandée : 18+)
- [XAMPP](https://www.apachefriends.org/fr/index.html) (ou un serveur Apache avec PHP et MySQL)
- [Composer](https://getcomposer.org/)
- [Git](https://git-scm.com/)
- [MongoDB](https://www.mongodb.com/try/download/community)

## 📂 1️⃣ Cloner le projet

Ouvrez un terminal et exécutez :

```sh
# Cloner le projet
git clone https://github.com/ton-utilisateur/ecoride.git

# Aller dans le dossier
cd ecoride
```

## ⚙️ 2️⃣ Configuration du Backend (PHP & MySQL)

### 📌 Créer la base de données MySQL

1. **Démarrer XAMPP** et s'assurer que **Apache** et **MySQL** sont activés.
2. **Ouvrir phpMyAdmin** ([http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)).
3. **Créer une base de données** `ecoride`.
4. **Exécuter le fichier SQL** pour créer les tables :

```sh
# Importer la base de données depuis le fichier SQL
mysql -u root -p ecoride < database/ecoride.sql
```

### 📌 Installer les dépendances PHP

Se placer dans le dossier **backend** et exécuter :

```sh
cd backend
composer install
```

### 📌 Configurer la base de données MySQL

Modifier le fichier **config/database.php** avec vos paramètres MySQL :

```php
$host = "127.0.0.1";
$dbname = "ecoride";
$username = "root";
$password = "";
```

### 📌 Lancer le serveur PHP

Démarrer le serveur PHP pour l'API :

```sh
php -S localhost:8000 -t public
```

L'API est maintenant accessible à l'adresse : [**http://localhost:8000**](http://localhost:8000)

## 📦 3️⃣ Configuration du Frontend (React)

### 📌 Installer les dépendances

Se placer dans le dossier **frontend** et installer les packages :

```sh
cd ../frontend
npm install
```

### 📌 Configurer l'URL de l'API

Modifier le fichier **frontend/src/api.js** avec l'URL de l'API :

```js
import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000',
});

export default api;
```

### 📌 Lancer l'application React

Démarrer l'application :

```sh
npm run dev
```

L'application sera accessible à [**http://localhost:5173**](http://localhost:5173).

## 📊 4️⃣ Configuration MongoDB (Avis des Passagers)

1. **Démarrer MongoDB** (si ce n'est pas fait) :

```sh
mongod --dbpath="C:\path\vers\mongodb\data"
```

2. **Vérifier que MongoDB est actif** avec la commande :

```sh
mongo
```

3. **Créer la base de données et la collection** :

```sh
use ecoride_reviews
```

L'API gérera les avis automatiquement.

## ✅ 5️⃣ Tester l'Application 🚀

- **Backend (PHP & API)** → [http://localhost:8000/](http://localhost:8000/)
- **Frontend (React)** → [http://localhost:5173/](http://localhost:5173/)
- **MongoDB (Avis)** → `use ecoride_reviews`

💡 **Astuce** : Vérifiez la console pour voir d'éventuelles erreurs.

---

## 🛠 Dépannage

### ❌ "Error: Connection refused to MySQL"

➡ **Solution** : Vérifiez que **MySQL est bien démarré** dans XAMPP.

### ❌ "MongoDB is not running"

➡ **Solution** : Assurez-vous que **MongoDB est bien lancé** (`mongod`).

### ❌ "CORS Policy Error in React"

➡ **Solution** : Ajoutez cette ligne dans `backend/public/index.php` :

```php
header("Access-Control-Allow-Origin: *");
```

## 🚀 Félicitations ! 🎉

Vous avez installé et exécuté **EcoRide** en local ! 🚗💨

💡 **Besoin d'aide ?** Ouvrez une issue sur [GitHub](https://github.com/ton-utilisateur/ecoride/issues) !

