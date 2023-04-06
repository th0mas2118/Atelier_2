# Atelier2

## Membres

### Pierson Thomas, DRAGUN Anthony, RAKOTOARISON Asley, DEMANGE Sébastien

Une branche par module (api_back, front et mobile)

## Installation globale:

Cloner chaque partie (mobile, front, back) séparément

Ajouter le vhost suivant :
`127.0.0.1 api.frontoffice.reunionou`

- Backend : se rendre dans Atelier_2/reunionou éxécuter `docker-compose up -d` (le backend est accessible depuis http://api.frontoffice.reunionou:49383)
- FrontEnd : se rendre dans Atelier_2/reunionou éxecuter `npm i` puis `npm run dev`. Vous devez également créer un fichier `.env.local` dans /reunionou (aux cotés des dossiers src et public), avec cette ligne: `VITE_API_HOST=http://api.frontoffice.reunionou:49383`.
- Mobile : se rendre dans Atelier_2/mobile éxécuter `flutter install`, `flutter pub get` puis `flutter run`

Documentation backend : https://webetu.iutnc.univ-lorraine.fr/www/pierso112u/Atelier_2/documentation/

## Bases de données

Vous pouvez accéder aux bases de données avec Compass:

- Auth: `mongodb://root:root@localhost:49317/reunionou?authSource=admin&readPreference=primary&appname=MongoDB%20Compass&directConnection=true&ssl=false`
- Event: `mongodb://root:root@localhost:49318/reunionou?authSource=admin&readPreference=primary&appname=MongoDB%20Compass&directConnection=true&ssl=false`
- Comment: `mongodb://root:root@localhost:49319/reunionou?authSource=admin&readPreference=primary&appname=MongoDB%20Compass&directConnection=true&ssl=false`

## Seeding

Dans le backend, un "seed_service" est disponible et permet d'ajouter des données randoms dans les bases de données. Il est accessible directement sur l'url du frontoffice (http://api.frontoffice.reunionou:49383).

Vous pouvez ajouter:

- Des users: POST `/users/seed` (un parametre `?count=xx` peut être fourni pour définir le nombre de données créées, 25 par défaut). Le mot de passe par défaut est 1234 pour tous les utilisateurs créés
- Des events (avec les invitations et les commentaires): POST `/events/seed` (`?count=xx` peut aussi être utilisé, 5 par défaut). La géneration des évenements peut être assez longue, alors il est conseillé d'y aller doucement.
