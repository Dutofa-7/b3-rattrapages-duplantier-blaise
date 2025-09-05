# Rattrapage Architecture des données : Création d'APIs

## Prérequis système

- **PHP 8.1+** avec extensions :
  - openssl
  - pdo_mysql
  - curl
  - mbstring
- **MySQL 8.0+**
- **Composer**
- **Git**
- **Symfony CLI**

## Installation

### 1. Cloner le projet
```bash
git clone https://github.com/Dutofa-7/b3-rattrapages-duplantier-blaise.git
cd b3-rattrapages-duplantier-blaise
```

### 2. Installer les dépendances PHP
```bash
composer install
```

### 3. Configuration de la base de données

#### Créer le fichier de configuration local
```bash
cp .env .env.local
```

#### Modifier la variable DATABASE_URL dans .env.local
```env
# Exemple pour MySQL local
DATABASE_URL="mysql://root:@127.0.0.1:3306/picard_api?serverVersion=8.0"
```

### 4. Créer et configurer la base de données
```bash
# Créer la base de données
php bin/console doctrine:database:create

# Appliquer les migrations
php bin/console doctrine:migrations:migrate

# Charger les données de test
php bin/console doctrine:fixtures:load
```

### 5. Démarrer le serveur de développement
```bash
# Avec Symfony CLI
symfony server:start
```

### 6. Interface web
```bash
Accédez à `http://localhost:8000` pour utiliser l'interface de test qui permet de :
- Créer un panier
- Ajouter des produits au panier
- Consulter le contenu du panier avec le total
- Supprimer des articles du panier
- Noter les produits
- Valider le panier
```

## Technologies utilisées

- **Framework** : Symfony 7.3
- **ORM** : Doctrine
- **Base de données** : MySQL 8.0
- **Templates** : Twig
- **Sérialisation** : Symfony Serializer
- **Validation** : Symfony Validator

## Vidéo de démonstration

