---
title: "Éditeur temps réel"
description: "CRDT, offline-first, livré seul en 6 semaines."
tags: ["yjs", "tiptap"]
variant: sm
mediaClass: media-2
order: 2
publishDate: 2024-06-01
client: "Startup EdTech, seed"
role: "Développeur fullstack"
duration: "6 semaines"
outcome: "Livré seul dans les délais, 0 conflit de données en prod"
---

## Le contexte

Une startup EdTech avait besoin d'un éditeur de documents collaboratif pour permettre à des équipes d'enseignants de co-rédiger du contenu pédagogique. Contrainte forte : les utilisateurs travaillent souvent en zones à connectivité dégradée (établissements scolaires avec VPN instables). L'éditeur devait fonctionner offline et synchroniser automatiquement à la reconnexion.

## Approche technique

### CRDT avec Yjs

Yjs est une implémentation de CRDT (Conflict-free Replicated Data Type) qui permet à plusieurs clients de modifier un document simultanément sans coordination centralisée. Les conflits sont résolus mathématiquement, pas par des verrous.

J'ai intégré Yjs avec Tiptap comme couche d'édition riche, et un provider WebSocket maison pour la synchronisation en temps réel — avec fallback sur IndexedDB pour la persistance offline.

### Architecture offline-first

```
Client A ←→ WebSocket Provider ←→ Server (Y-WebSocket)
              ↕                         ↕
         IndexedDB                  PostgreSQL
         (offline)                  (source of truth)
```

Le document est toujours lu depuis le store local. La synchronisation réseau est opportuniste : si le serveur est disponible, on sync ; sinon, on continue à écrire localement.

## Résultats

- Aucun conflit de données signalé en production depuis le lancement
- Latence de synchronisation < 80ms sur connexion correcte
- Reprise offline transparente après coupure réseau testée jusqu'à 48h
- Livré seul en 6 semaines, dans les délais convenus
