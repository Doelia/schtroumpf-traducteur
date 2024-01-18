# Schtroumpfizer

Transforme des phrases dans la langue des schtroumpfs en utilisant des modèles IA en natural language.


Exemple en entrée :
```
Sauf erreur, nous n’avons pas eu de réponse concernant le problème relevé hier matin.
```

Sortie :
```
Sauf erreur, nous n’avons pas schtroumpfé de réponse schtroumpfant le problème schtroumpfé hier matin.
```

Disponible librement en ligne via interface graphique ou API sur https://schtroumpfizer.doelia.fr/


## Développement

Dépendances :
- PHP 8.3
- Symfony CLI
- Composer
- NodeJS 20 / npm

Installation :
```
composer install
npm instal
```

Complilation à la volée du js/scss avec vite :
```
npm run dev
```

Lancement du serveur Symfony :
```
symfony serve
```

Puis accéder à l'application sur http://localhost:8000/

### Outils utilisés
- [Spacy API](https://github.com/doelia/spacy-api) pour la classification des mots
