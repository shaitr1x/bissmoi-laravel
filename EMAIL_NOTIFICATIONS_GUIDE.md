# Guide des Notifications Email - BISSMOI

## Vue d'ensemble

Le système de notifications email de BISSMOI permet d'envoyer automatiquement des emails aux utilisateurs en complément des notifications dans l'interface. Le système est intégré et utilise le service `NotificationService` pour gérer les notifications en double canal (interface + email).

## Configuration

### 1. Configuration Email de Base

Dans votre fichier `.env`, configurez les paramètres de messagerie :

```env
# Pour la production avec un vrai serveur SMTP (ex: Gmail, SendGrid, etc.)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=votre-email@gmail.com
MAIL_PASSWORD=votre-mot-de-passe-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@bissmoi.com"
MAIL_FROM_NAME="BISSMOI"

# Pour les tests locaux (emails sauvés dans les logs)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@bissmoi.com"
MAIL_FROM_NAME="BISSMOI"
```

### 2. Configuration Avancée

Pour personnaliser davantage les emails, vous pouvez modifier la classe `UserEmailNotification` dans `app/Notifications/UserEmailNotification.php`.

## Utilisation

### 1. Via le Service NotificationService

```php
use App\Services\NotificationService;

// Dans un contrôleur
public function __construct(NotificationService $notificationService)
{
    $this->notificationService = $notificationService;
}

// Envoyer une notification avec email
$this->notificationService->sendToUser(
    $userId,
    'Titre de la notification',
    'Message de la notification',
    'info', // Type: info, success, warning, error
    'bell', // Icône
    ['key' => 'value'], // Métadonnées optionnelles
    true // Envoyer email (true/false)
);

// Envoyer à plusieurs utilisateurs
$this->notificationService->sendToUsers(
    [$userId1, $userId2],
    'Titre',
    'Message',
    'success',
    'check',
    null,
    true
);

// Envoyer à tous les utilisateurs
$this->notificationService->sendToAllUsers(
    'Titre global',
    'Message pour tous',
    'info',
    'megaphone',
    null,
    true
);
```

### 2. Types de Notifications Disponibles

- `info` : Informations générales (bleu)
- `success` : Actions réussies (vert)
- `warning` : Avertissements (jaune)
- `error` : Erreurs (rouge)

### 3. Icônes Disponibles

Le système utilise Heroicons. Exemples d'icônes courantes :
- `bell` : Notification générale
- `truck` : Livraison/Expédition
- `package` : Colis/Commande
- `check-circle` : Validation/Succès
- `store` : Boutique/Marchand
- `megaphone` : Annonce publique

## Notifications Automatiques Existantes

Le système envoie automatiquement des notifications email pour :

### Commandes
- **Commande validée** : Quand un marchand valide une commande
- **Commande expédiée** : Quand un marchand marque une commande comme expédiée  
- **Commande livrée** : Quand un marchand confirme la livraison

### Compte Marchand
- **Demande marchand envoyée** : Confirmation de demande de statut marchand

## Tests

### 1. Commande Artisan de Test

```bash
# Tester avec le premier utilisateur de la DB
php artisan test:email-notification

# Tester avec un utilisateur spécifique
php artisan test:email-notification 1
```

### 2. Vérification des Logs

Avec `MAIL_MAILER=log`, les emails sont sauvés dans `storage/logs/laravel.log` :

```bash
# Windows PowerShell
Get-Content -Tail 50 storage/logs/laravel.log

# Linux/Mac
tail -50 storage/logs/laravel.log
```

### 3. Script de Test Standalone

Un script `test_email_notification.php` est disponible à la racine du projet pour des tests rapides.

## Personnalisation des Templates Email

### 1. Template de Base

Le template principal se trouve dans `app/Notifications/UserEmailNotification.php` dans la méthode `toMail()`.

### 2. Personnalisation Avancée

Pour des modifications plus poussées, vous pouvez :

1. Créer des vues Blade personnalisées
2. Modifier les couleurs et styles dans la classe
3. Ajouter des boutons d'action personnalisés

Exemple de bouton d'action :
```php
->action('Voir ma commande', url('/orders/' . $orderId))
```

## Intégration avec l'Interface

Le système fonctionne en parallèle avec les notifications dans l'interface :

1. **Notification créée dans l'interface** → Visible dans le menu utilisateur
2. **Email envoyé simultanément** → Reçu dans la boîte email
3. **Même contenu et style** → Cohérence de l'expérience utilisateur

## Dépannage

### Problèmes Courants

1. **Emails non reçus**
   - Vérifier la configuration SMTP
   - Tester avec `MAIL_MAILER=log` d'abord
   - Vérifier les logs d'erreur

2. **Erreur de connexion SMTP**
   - Vérifier les paramètres du serveur
   - S'assurer que le port n'est pas bloqué
   - Utiliser un mot de passe d'application pour Gmail

3. **Template mal affiché**
   - Vérifier la syntaxe dans `UserEmailNotification.php`
   - Tester avec un email simple d'abord

### Logs Utiles

```bash
# Logs généraux Laravel
Get-Content storage/logs/laravel.log

# Vider le cache de configuration
php artisan config:clear
php artisan config:cache
```

## Sécurité

- Les emails utilisent le nom d'expéditeur "BISSMOI" pour éviter le spam
- Les données sensibles ne sont jamais incluses dans les emails
- Les liens pointent toujours vers des pages sécurisées du site

## Performance

- Le service est optimisé pour les envois en lot
- Les métadonnées sont optionnelles pour réduire la taille
- La queue peut être configurée pour les gros volumes

---

Pour plus d'aide ou des questions spécifiques, contactez l'équipe de développement BISSMOI.
