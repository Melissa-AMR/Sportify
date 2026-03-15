# SPORTIFY

> Plateforme web de reservation et de gestion d'activites sportives

---

## Presentation

Sportify est une application web full-stack permettant aux utilisateurs de parcourir un catalogue d'activites sportives, de constituer un panier, de generer un devis personnalise et de proceder au paiement en ligne. Le projet integre une gestion complete des utilisateurs avec authentification, ainsi qu'un systeme d'avis et de notation.

## Stack technique

| Couche | Technologies |
|--------|-------------|
| Back-end | PHP 8 · PDO · MySQL |
| Front-end | HTML5 · CSS3 · JavaScript |
| Frameworks & Librairies | Bootstrap 5.3 · Font Awesome 6.5 |
| Environnement | XAMPP (Apache + MySQL) |

## Fonctionnalites

**Gestion utilisateurs**
- Inscription et connexion securisees (sessions PHP, mots de passe hashes)

**Catalogue & Reservation**
- Consultation des activites avec filtres dynamiques
- Panier interactif : ajout, suppression, selection du niveau (debutant / intermediaire / avance)

**Devis & Paiement**
- Generation de devis personnalise (cours collectif ou individuel)
- Recapitulatif avec calcul automatique du montant
- Envoi du devis par email
- Interface de paiement securise

**Avis clients**
- Notation par etoiles (1 a 5) par activite
- Affichage des temoignages sur la page d'accueil

**Interface**
- Design responsive adapte mobile, tablette et desktop
- Charte graphique coherente (rouge #e50914, noir, blanc)

## Arborescence

```
Sportify/
├── accueil.php            Page d'accueil
├── activities.php         Catalogue des activites
├── cart.php               Panier
├── devis.php              Devis personnalise
├── paiement.php           Paiement
├── login.php              Connexion
├── register.php           Inscription
├── config.example.php     Configuration BDD (modele)
├── header.php / footer.php
├── sportify.sql           Export de la base de donnees
├── style/                 Feuilles de style
├── JavaScript/            Scripts
└── images/                Ressources visuelles
```

## Installation et lancement

**Prerequis** : [XAMPP](https://www.apachefriends.org/) installe

1. **Cloner le depot**
   ```
   git clone https://github.com/Melissa-AMR/Sportify.git
   ```
   Placer le dossier dans `htdocs/` de XAMPP.

2. **Importer la base de donnees**
   - Ouvrir phpMyAdmin → http://localhost/phpmyadmin
   - Creer une base nommee `sportify`
   - Onglet **Importer** → selectionner `sportify.sql` → Executer

3. **Configurer la connexion**
   - Renommer `config.example.php` en `config.php`
   - Adapter les identifiants BDD si necessaire

4. **Lancer**
   - Demarrer Apache et MySQL dans le panneau XAMPP
   - Acceder a http://localhost/Sportify/

## Auteur

**Melissa AMR**
