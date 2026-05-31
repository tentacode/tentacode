---
title: "Audit RGAA + remediation"
description: "App grand public 40 écrans, liste priorisée + PRs."
tags: ["audit", "RGAA"]
variant: sm
mediaClass: media-4
order: 4
publishDate: 2023-11-10
client: "Application grand public, 1M+ utilisateurs"
role: "Expert accessibilité"
duration: "6 semaines"
outcome: "Conformité RGAA AA sur les parcours critiques"
---

## Le contexte

Une application web grand public (services en ligne pour particuliers) devait atteindre la conformité RGAA niveau AA pour répondre aux obligations légales. Le périmètre : 40 écrans couvrant les parcours principaux — inscription, consultation, et les 3 actions métier les plus fréquentes.

## Méthodologie

### Phase 1 : Audit automatisé + manuel (2 semaines)

J'ai combiné plusieurs outils pour obtenir une couverture maximale :

- **axe-core** via extension Chrome pour les erreurs détectables automatiquement (~30% des critères RGAA)
- **Tests manuels au clavier** sur chaque écran : focus visible, ordre de tabulation, pièges à focus
- **Tests avec lecteurs d'écran** : NVDA+Firefox (référence RGAA), VoiceOver+Safari (iOS)
- **Vérification des contrastes** avec le Color Contrast Analyzer

### Phase 2 : Rapport et priorisation

Le rapport final contenait 147 non-conformités classées en 3 niveaux :

| Niveau | Critères | Non-conformités |
|--------|----------|-----------------|
| Bloquant | 12 critères RGAA | 31 |
| Majeur | 18 critères RGAA | 67 |
| Mineur | 8 critères RGAA | 49 |

Chaque non-conformité incluait : description, capture d'écran, critère RGAA concerné, et suggestion de correction avec exemple de code.

### Phase 3 : Remédiation (4 semaines)

J'ai livré des PRs directement dans le dépôt de l'équipe pour les 31 points bloquants, avec :
- Le correctif
- Un test automatisé pour éviter la régression
- Un commentaire expliquant le critère RGAA et pourquoi la correction est nécessaire

## Résultats

- 31/31 non-conformités bloquantes corrigées
- Conformité RGAA AA attestée sur les 5 parcours critiques
- L'équipe a corrigé 40 des 67 non-conformités majeures de façon autonome grâce à la documentation fournie
- Déclaration d'accessibilité publiée sur le site
