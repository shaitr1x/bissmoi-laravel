# Configuration et utilisation de Campay

## Description
L'intégration Campay permet d'accepter les paiements via MTN Mobile Money et Orange Money au Cameroun directement sur la plateforme Bissmoi.

## Installation et Configuration

### 1. Prérequis
- Compte Campay actif avec API access
- Clés d'API Campay (App ID, App Secret, Username, Password)

### 2. Configuration des variables d'environnement
Modifier le fichier `.env` avec vos vraies clés API :

```env
CAMPAY_API_URL=https://www.campay.net/api
CAMPAY_APP_ID=votre_app_id_campay
CAMPAY_APP_SECRET=votre_app_secret_campay
CAMPAY_APP_USERNAME=votre_username_campay
CAMPAY_APP_PASSWORD=votre_password_campay
CAMPAY_ENVIRONMENT=sandbox  # ou production
```

### 3. Test de l'intégration

#### Côté utilisateur :
1. Passer une commande et choisir "Paiement mobile"
2. Saisir le numéro de téléphone MTN/Orange
3. Cliquer sur "Confirmer la commande"
4. Sur la page de détails, cliquer sur "Payer maintenant"
5. Suivre les instructions USSD qui s'affichent

#### Côté administrateur :
- Voir les statuts de paiement dans l'admin
- Recevoir les notifications de paiement confirmé/échoué

## Fonctionnalités implémentées

### Services
- `CampayService` : Gestion des appels API (authentification, initiation, vérification)

### Contrôleurs
- `CampayController` : Gestion des paiements (initiation, vérification, webhook)

### Vues
- Modal de paiement mobile dans `orders/show`
- Options de paiement dans `checkout`
- Statuts de paiement dans les détails de commande

### Routes
- `POST /campay/initiate` : Initier un paiement
- `POST /campay/check-status` : Vérifier le statut
- `POST /campay/webhook` : Recevoir les notifications Campay

## Base de données
Champs ajoutés à la table `orders` :
- `payment_method` : 'campay' ou 'cash_on_delivery'  
- `payment_reference` : Référence Campay
- `payment_status` : 'pending', 'completed', 'failed', 'cancelled'

## Sécurité
- CSRF protection sur toutes les routes
- Validation des données utilisateur
- Logs des transactions
- Vérification de propriétaire des commandes

## Test
Pour tester sans vraies clés API :
1. Garder `CAMPAY_ENVIRONMENT=sandbox`
2. Utiliser des numéros de test Campay
3. Vérifier les logs dans `storage/logs/laravel.log`

## Support des opérateurs
- MTN Mobile Money (Cameroun)
- Orange Money (Cameroun)

## Monitoring
- Logs des paiements dans les fichiers de log Laravel
- Statuts visibles dans l'interface admin
- Webhooks pour les mises à jour automatiques

## Dépannage

### Erreur d'authentification
- Vérifier les clés API dans `.env`
- Vérifier que l'environnement (sandbox/production) est correct

### Paiement bloqué
- Vérifier le format du numéro de téléphone
- Vérifier le solde du compte mobile
- Consulter les logs Campay

### Webhook non reçu
- Vérifier que l'URL webhook est accessible
- Configurer l'URL dans le dashboard Campay
- Vérifier les logs du serveur web
