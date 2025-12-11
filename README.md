Voici ton README complété proprement avec les informations de connexion, prêt à être poussé sur GitHub.

---

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

## Instructions de connexion

### URL d’accès à l'application

Accéder à l’interface web Symfony via :

[http://localhost:8000/login](http://localhost:8000/login)

### Identifiants par défaut (créés automatiquement via Fixtures)

Un tuteur administrateur est généré lors du chargement des Fixtures :

* **Email :** `admin@example.com`
* **Mot de passe :** `password`

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
