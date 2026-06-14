---
title: "Vivre un mois avec un agent dans son terminal."
description: "Bilan honnête après 30 jours à coder en tandem avec Claude Code : ce qui change vraiment, et ce qui n'a pas changé du tout."
slug: agent-claude-au-quotidien
publishDate: 2026-03-12
category: "AI"
categoryIcon: ai
---

Ça fait maintenant un mois que Claude Code tourne en permanence dans mon terminal. Pas en mode "je l'ouvre quand j'ai un bug compliqué" — en mode agent intégré au flux de travail, qui lit le codebase, propose des changements, lance les tests.

Voici ce que j'ai appris.

## Ce qui change vraiment

**La vitesse sur les tâches répétitives.** Refactoriser un composant pour en extraire une sous-partie, écrire un test pour une fonction déjà documentée, migrer un pattern vers un nouveau : tout ça va deux à trois fois plus vite. Pas parce que l'agent est magique, mais parce qu'il ne souffre pas de l'inertie mentale du "j'ai pas envie de toucher à ce fichier".

**La réduction du coût cognitif des petites décisions.** Choisir un nom de variable, décider si une constante mérite son propre fichier, écrire un message de commit clair : autant de micro-décisions qui fatiguent. L'agent propose, tu valides ou tu ajustes. C'est un gain réel sur la durée d'une journée.

**Le contexte long.** L'agent lit plusieurs fichiers en parallèle avant de répondre. Ça lui permet de proposer des solutions cohérentes avec le reste du code, pas des réponses génériques copiées-collées depuis Stack Overflow.

## Ce qui n'a pas changé

**La compréhension du domaine métier.** L'agent ne sait pas que cette règle de gestion existe parce qu'un client s'est plaint en 2023. Il ne sait pas que ce service est critique et ne doit surtout pas être refactorisé avant la v3. Ce contexte implicite, c'est toi qui le portes.

**La responsabilité de la qualité.** L'agent génère du code plausible. Pas forcément du bon code. La différence entre les deux, c'est ton regard critique. Si tu arrêtes de relire, tu introduces des bugs — juste plus vite.

**Le plaisir de résoudre un problème difficile.** Déboguer un memory leak dans un contexte concurrent, concevoir une architecture qui tiendra cinq ans, trouver pourquoi ce test flaky échoue une fois sur cinquante : ce genre de problème reste entièrement humain. Et honnêtement, c'est tant mieux.

## Mon setup

J'utilise Claude Code directement en CLI, avec des règles dans `CLAUDE.md` pour qu'il respecte les conventions du projet. Je lui donne accès aux outils bash en lecture (grep, find, git log) et je valide les writes manuellement. Pour les tâches complexes, je lance des sous-agents en parallèle.

## Conclusion

L'agent n'est pas un junior qu'on peut laisser tourner seul. C'est plutôt un outil de pensée externe, très rapide, qu'on doit superviser. L'analogie qui me semble la plus juste : c'est comme avoir un deuxième cerveau qui ne se fatigue pas, mais qui n'a aucun bon sens sans le tien.
