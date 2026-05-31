---
title: "App mobile accessible"
description: "React Native, audit VoiceOver / TalkBack inclus."
tags: ["react native", "a11y"]
variant: sm
mediaClass: media-6
order: 6
publishDate: 2024-04-22
client: "Mutuelle santé, app iOS + Android"
role: "Développeur mobile & expert a11y"
duration: "3 mois"
outcome: "Conformité RGAA mobile sur les parcours principaux"
---

## Le contexte

Une mutuelle santé voulait rendre son application mobile (React Native, iOS + Android) accessible à ses adhérents en situation de handicap visuel. L'application permettait de consulter ses remboursements, télécharger des attestations et contacter le service client — des fonctions critiques qui devaient être utilisables avec VoiceOver (iOS) et TalkBack (Android).

## Défis spécifiques au mobile

L'accessibilité mobile avec React Native présente des défis propres :

- Les composants React Native ont des comportements d'accessibilité différents entre iOS et Android
- Les gestes natifs (swipe, pinch) doivent coexister avec les gestes du lecteur d'écran
- Les `TouchableOpacity` et `Pressable` ne propagent pas toujours les rôles ARIA correctement

## Ce que j'ai fait

### Audit avec vrais appareils

J'ai testé l'application sur iPhone (VoiceOver) et un Android physique (TalkBack) — pas de simulateurs, les lecteurs d'écran mobiles ont des comportements qui ne se reproduisent pas fidèlement en simulation.

### Corrections systémiques

```tsx
// Avant : bouton sans label accessible
<TouchableOpacity onPress={downloadPDF}>
  <Image source={icons.download} />
</TouchableOpacity>

// Après : rôle, label, et hint explicites
<TouchableOpacity
  onPress={downloadPDF}
  accessible={true}
  accessibilityRole="button"
  accessibilityLabel="Télécharger l'attestation"
  accessibilityHint="Ouvre un PDF dans votre application de lecture"
>
  <Image source={icons.download} accessibilityElementsHidden={true} />
</TouchableOpacity>
```

Les listes de remboursements ont été restructurées pour que chaque ligne soit une unité sémantique cohérente — au lieu d'annoncer "Médecin" puis "85,00 €" puis "Remboursé" séparément, le lecteur d'écran annonce "Consultation médecin, 85 euros, remboursé le 12 mars".

## Résultats

- Parcours principaux navigables de bout en bout au lecteur d'écran sur iOS et Android
- 0 blocage identifié sur les 5 parcours critiques
- Rapport de test livré avec scripts de test pour non-régressions futures
