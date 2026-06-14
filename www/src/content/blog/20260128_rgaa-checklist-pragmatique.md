---
title: "Une checklist RGAA pragmatique, en 20 lignes."
description: "Pas de magie : juste les bons réflexes à intégrer dans la PR, le code review et la recette. Ça épargne 80% des galères en fin de projet."
slug: rgaa-checklist-pragmatique
publishDate: 2026-01-28
category: "Accessibilité"
categoryIcon: a11y
---

Le RGAA, c'est 106 critères. En pratique, 80% des non-conformités observées en audit viennent d'une petite dizaine de problèmes récurrents. Voici ma checklist de terrain.

## Avant de merger une PR

Ces points prennent moins de 5 minutes à vérifier manuellement. Sans eux, l'audit va être douloureux.

### 1. Tout élément interactif est atteignable au clavier

Navigue à la tab. Tu dois pouvoir atteindre tous les boutons, liens, champs. L'ordre doit être logique. Le focus doit être visible à tout moment.

```css
/* Ne jamais faire ça */
:focus { outline: none; }

/* À la place */
:focus-visible {
  outline: 3px solid #4A90E2;
  outline-offset: 2px;
}
```

### 2. Les images ont un attribut alt

- Image informative → alt qui décrit l'image
- Image décorative → `alt=""`
- Image-lien → alt qui décrit la destination

```html
<!-- Informative -->
<img src="photo.jpg" alt="Gabriel Pillet lors de sa conférence au Paris Web 2024">

<!-- Décorative -->
<img src="separator.svg" alt="">

<!-- Lien -->
<a href="/"><img src="logo.svg" alt="Retour à l'accueil"></a>
```

### 3. Le contraste est suffisant

Ratio minimum : 4.5:1 pour le texte normal, 3:1 pour le grand texte (18px+ bold ou 24px+). [Colour Contrast Analyser](https://www.tpgi.com/color-contrast-checker/) fait ça en 10 secondes.

### 4. Les formulaires ont des labels

Chaque `<input>`, `<select>`, `<textarea>` doit avoir un `<label>` associé via `for`/`id`, ou un `aria-label`. Le placeholder ne compte pas comme label.

### 5. Les titres forment une hiérarchie logique

Un seul `<h1>` par page. Pas de saut de niveau (h1 → h3 sans h2). Les titres décrivent le contenu qui suit.

### 6. Les liens ont un intitulé explicite

"Cliquez ici", "Lire la suite", "En savoir plus" : non. Si plusieurs liens ont le même texte visible, ils doivent avoir des `aria-label` distincts.

### 7. Les vidéos ont des sous-titres

Et si elles se lancent automatiquement, elles doivent être muettes par défaut.

## En recette

Deux outils gratuits, cinq minutes :

1. **axe DevTools** (extension Chrome) : lance un audit automatique. Il ne trouve pas tout, mais il ne génère aucun faux positif.
2. **Tab seul** : parcours la page entière sans souris. Tu dois pouvoir tout faire.

## Ce que cette liste ne remplace pas

Un audit complet avec des technologies d'assistance (NVDA, VoiceOver) reste nécessaire pour certifier la conformité RGAA. Cette checklist sert à éviter les erreurs grossières dès la phase de développement — pas à remplacer l'expertise d'un auditeur.

Mais sur un projet standard, intégrer ces réflexes dès le code review, c'est éliminer la majorité des problèmes avant qu'ils n'arrivent en audit.
