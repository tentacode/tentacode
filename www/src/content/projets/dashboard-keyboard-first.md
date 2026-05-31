---
title: "Dashboard SaaS — refonte clavier-first"
description: "Refonte d'un dashboard B2B de 200 écrans pour qu'il soit pleinement navigable au clavier et au lecteur d'écran. Score Lighthouse a11y : 64 → 100."
tags: ["react", "aria", "audit", "RGAA"]
variant: featured
mediaClass: media-1
order: 1
publishDate: 2024-03-15
client: "SaaS B2B, 200k utilisateurs"
role: "Lead technique & dev front"
duration: "4 mois"
outcome: "Score Lighthouse a11y 64 → 100"
---

## Le contexte

Un éditeur de logiciels B2B m'a contacté après un audit interne qui révélait des lacunes sévères en accessibilité : navigation au clavier impossible sur une grande partie des écrans, composants interactifs non annoncés aux lecteurs d'écran, contrastes insuffisants sur les tableaux de bord critiques.

L'enjeu était double : répondre aux exigences RGAA pour leurs clients grands comptes (dont plusieurs organismes publics), et améliorer l'expérience de tous les utilisateurs — la navigation au clavier profite autant aux personnes en situation de handicap qu'aux power-users.

## Ce que j'ai fait

### Audit initial

J'ai commencé par un audit complet des 200 écrans avec une combinaison d'outils automatiques (axe, Lighthouse) et de tests manuels au clavier et avec NVDA/VoiceOver. J'ai produit un rapport priorisé avec trois niveaux : bloquant, majeur, mineur.

### Plan de remédiation

Plutôt que de traiter les problèmes écran par écran, j'ai identifié les patterns récurrents et conçu des corrections systémiques :

- Refonte du système de focus avec un indicateur visible cohérent sur toute l'application
- Abstraction des composants interactifs (modals, dropdowns, tooltips) en suivant les patterns WAI-ARIA
- Correction des tableaux de données avec headers et scope corrects
- Mise en place de skip links et de régions ARIA sur le layout principal

### Implémentation

J'ai travaillé en binôme avec un développeur de l'équipe pour transférer les compétences en même temps qu'on corrigeait. Chaque correction était accompagnée d'un test automatisé pour éviter les régressions.

```tsx
// Avant : focus trap inexistant sur la modal
function Modal({ children }) {
  return <div className="modal">{children}</div>;
}

// Après : focus trap, rôle dialog, aria-labelledby
function Modal({ title, children, onClose }) {
  const titleId = useId();
  useFocusTrap();
  return (
    <div role="dialog" aria-modal="true" aria-labelledby={titleId}>
      <h2 id={titleId}>{title}</h2>
      {children}
    </div>
  );
}
```

## Résultats

- Score Lighthouse accessibilité : **64 → 100** sur les écrans principaux
- 0 erreur axe sur les 40 écrans les plus critiques
- Conformité RGAA niveau AA atteinte, documentée pour les appels d'offres publics
- L'équipe est autonome : revues de code avec checklist a11y intégrée au process
