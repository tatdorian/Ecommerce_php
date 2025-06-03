Librairie en Ligne - E-commerce PHP

Une application web de e-commerce spécialisée dans la vente de livres d'occasion, développée en PHP avec une base de données MySQL.

🌟 Fonctionnalités

👤 Gestion des utilisateurs

- Inscription et connexion sécurisée avec hachage des mots de passe
- Profil utilisateur avec photo de profil (base64)
- Modification du profil (nom, email, mot de passe, photo)
- Système de rôles et gestion du solde

📖 Gestion des livres

- Ajout de livres à vendre avec informations détaillées
- Catalogue complet avec recherche et navigation
- Pages de détail pour chaque livre
- Modification et suppression des annonces
- Archivage des anciennes annonces

🛒 Système de panier

- Ajout au panier avec gestion des quantités
- Modification des quantités dans le panier
- Suppression d'articles du panier
- Calcul automatique du total

🎨 Interface utilisateur

- Design moderne avec effets visuels avancés
- Responsive design pour mobile et desktop
- Animations fluides et transitions CSS
- Thème cohérent avec gradients et glassmorphism

🛠️ Technologies utilisées

- Backend : PHP 7.4+
- Base de données : MySQL
- Frontend : HTML5, CSS3, JavaScript
- Serveur : Apache (XAMPP)
- Architecture : MVC (Model-View-Controller)

    🚀 Installation et configuration

- XAMPP (Apache + MySQL + PHP)
- Navigateur web moderne

Étapes d'installation

1. Télécharger et installer XAMPP

    https://www.apachefriends.org/

2. Cloner le projet 

    git clone https://github.com/tatdorian/Ecommerce_php.git

3. Placer le projet dans XAMPP

    Copier le dossier 'ecommerce_php' dans : C:\xampp\htdocs\

    
4. Démarrer les services XAMPP

    - Ouvrir XAMPP Control Panel
    - Démarrer Apache et MySQL

5. Créer la base de données

    - Accéder à phpMyAdmin : http://localhost/phpmyadmin
    - Créer une base de données nommée ecommerce
    - Importer le schéma de base de données (voir section Base de données)

6. Vérifier les parametres de connection à la bdd dans configs/db.config.php

    $host = 'localhost';
    $db   = 'ecommerce';
    $user = 'root';
    $pass = ''; 
    $charset = 'utf8mb4';

7. Accéder à l'application

    http://localhost/ecommerce_php/src/pages/login.page.php


@Authors    Tatoulian Dorian    Flandrin Hugo   Diop Amadou