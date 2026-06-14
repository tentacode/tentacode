---
title: "Monorepo : quand ça aide, quand ça ralentit."
description: "Retour d'expérience sur trois ans de monorepo avec une équipe de 8 personnes. Les bénéfices réels, les pièges, et les questions à se poser avant de se lancer."
slug: monorepo-pragmatique
publishDate: 2025-11-05
category: "Lead"
categoryIcon: lead
---

Le monorepo est devenu la recommandation par défaut pour les équipes fullstack. Nx, Turborepo, pnpm workspaces — les outils sont matures, les témoignages enthousiastes abondent. Après trois ans à en gérer un avec une équipe de 8 personnes, voici un bilan plus nuancé.

## Ce qu'un monorepo résout vraiment

### Les dépendances partagées

C'est le vrai argument. Quand le frontend et le backend partagent des types TypeScript, des constantes métier, des utilitaires de validation — avoir tout dans le même repo élimine une classe entière de problèmes de désynchronisation.

Sans monorepo, cette synchronisation se fait via des packages npm internes (lents à publier, faciles à oublier) ou par copier-coller (source de divergence garantie). Avec un monorepo, l'import est direct et le type-checking est immédiat.

### Le refactoring cross-package

Renommer une interface qui est utilisée dans cinq packages différents. Migrer une API interne. Déplacer de la logique du client vers le serveur. Ces opérations sont infiniment plus simples quand tout le code est dans le même espace de travail.

### L'onboarding

Un seul repo à cloner, un seul `npm install`, un seul CI à comprendre. Pour un nouveau membre de l'équipe, c'est un gain de temps mesurable.

## Ce qu'un monorepo ne résout pas

### La complexité organisationnelle

Si vos équipes sont mal coordonnées, un monorepo ne va pas arranger ça. Au contraire — tout le monde voit les changements de tout le monde, les conflits de merge augmentent, et les conventions non écrites deviennent des sujets de friction permanents.

Un monorepo amplifie la communication. Dans une équipe qui communique bien, c'est un multiplicateur. Dans une équipe qui communique mal, c'est un accélérateur de conflits.

### Les temps de build

Les outils de build incrémental (Turborepo, Nx) font des miracles, mais il reste un overhead. La CI qui prenait 4 minutes dans un repo isolé en prend 12 dans le monorepo complet, même avec le cache. À mesure que le repo grandit, ce chiffre augmente.

### La gouvernance des packages

Qui décide quand créer un nouveau package ? Quelles sont les règles de dépréciation ? Qui est responsable de `@company/utils` quand trois équipes l'utilisent ? Ces questions existent en multi-repo aussi, mais elles deviennent plus urgentes dans un monorepo parce que les frictions sont plus immédiates.

## Les questions à se poser avant

**Vos packages partagent-ils vraiment du code ?** Si le frontend et le backend n'ont aucune logique commune, un monorepo n'apporte pas grand-chose et complexifie la CI inutilement.

**Votre équipe est-elle à l'aise avec git ?** Les monorepos génèrent plus de conflits de merge et des historiques git plus complexes. Une équipe junior peut se retrouver à passer beaucoup de temps à démêler des situations qu'elle ne comprend pas.

**Avez-vous un champion ?** Un monorepo sans quelqu'un pour le maintenir activement (mettre à jour les outils, définir les conventions, nettoyer les packages obsolètes) devient rapidement un terrain vague.

## Mon bilan

Pour l'équipe et le projet sur lesquels j'ai travaillé — fullstack TypeScript, partage important de types métier, équipe soudée et habituée à git — le monorepo était le bon choix. Les bénéfices ont dépassé les coûts.

Mais je l'aurais déconseillé si l'équipe avait été plus junior, si les stacks technique front/back avaient été plus hétérogènes, ou si on n'avait pas eu de temps à investir dans la maintenance de l'outillage.

C'est un outil, pas une doctrine.
