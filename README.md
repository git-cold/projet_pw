Guillaume Boutigny & Séha Hassane
TD-1, TP-B

# Mini-Projet – Visites Tuteurs

## Instructions d’installation

### 1. Arrêter les conteneurs existants

*(si des conteneurs tournent déjà)*

```bash
docker compose down --remove-orphans
```

### 2. Rebuild complet de l’image Symfony

```bash
docker compose build --no-cache app
```

### 3. Lancer l’environnement

```bash
docker compose up -d
```

---

## Redémarrage du serveur Symfony

Si vous arrêtez manuellement le serveur Symfony dans le conteneur,
vous pouvez le relancer rapidement grâce à la commande suivante :

```bash
composer start-server
```

Cette commande exécute automatiquement :

```
symfony server:start --no-tls --port=8000 --allow-all-ip
```

---

## Instructions de connexion

### URL d’accès à l'application

Accéder à l’interface web Symfony via :

[http://localhost:8000/login](http://localhost:8000/login)

### Identifiants par défaut (créés automatiquement via Fixtures)

Un tuteur administrateur est généré lors du chargement des Fixtures :

* **Email :** 
```
admin@example.com
```

* **Mot de passe :** 
```
password
```

Ces informations proviennent de la fixture suivante :

```php
    $tuteur = new Tuteur();
    $tuteur->setNom('Admin');
    $tuteur->setPrenom('Super');
    $tuteur->setEmail('admin@example.com');
    $tuteur->setTelephone('0600000000');

    // Mot de passe hashé
    $hashedPassword = password_hash('password', PASSWORD_DEFAULT);
    $tuteur->setPassword($hashedPassword);
```

## **Stack**

* PHP 8.1 
* Symfony
* MySQL 8.0
* phpMyAdmin

## **Fonctionnalités implémentées**

### **Authentification**

* Connexion par email + mot de passe
* Déconnexion
* Mot de passe hashé
* Session Symfony

### **Étudiants**

* CRUD complet :

  * Ajouter un étudiant
  * Modifier un étudiant
  * Supprimer un étudiant
  * Lister les étudiants du tuteur
* Association automatique au tuteur connecté
* Validation via FormTypes

### **Visites**

* CRUD complet :

  * Ajouter une visite (préremplie avec étudiant + tuteur + statut par défaut)
  * Modifier une visite
  * Supprimer une visite
  * Lister les visites d’un étudiant
* Filtrage par statut :

  * Prévue
  * Réalisée
  * Annulée
* Tri par date (ascendant / descendant)
* FormType Visite

### **Compte-rendu**

* Ajout / modification du compte-rendu
* Export PDF via DomPDF

### **Front-end**

* Interface Twig
* Mise en forme avec Bootstrap
* Navbar dynamique avec bouton de déconnexion
* Layout global

### **API Platform**

* Ressource Tuteur exposée

