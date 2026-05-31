---
title: "Refonte checkout e-commerce"
description: "Lead d'une équipe de 3, drop-off réduit de 22%."
tags: ["next.js", "stripe"]
variant: sm
mediaClass: media-3
order: 3
publishDate: 2024-01-20
client: "E-commerce mode, 500k visites/mois"
role: "Lead technique (équipe de 3)"
duration: "3 mois"
outcome: "Taux de drop-off réduit de 22%"
---

## Le contexte

Un e-commerce de mode avec 500k visites mensuelles souffrait d'un taux d'abandon au checkout anormalement élevé (72%). L'analyse des sessions Hotjar montrait des frictions importantes : formulaire en une seule longue page, récapitulatif panier absent, erreurs de validation peu claires.

## Ce que j'ai fait

J'ai pris le lead technique d'une équipe de trois développeurs pour refondre entièrement le tunnel d'achat.

### Diagnostic

Avant de toucher au code, j'ai passé une semaine à analyser :
- Les enregistrements de sessions (Hotjar)
- Les données analytiques d'entonnoir (GA4)
- 12 entretiens utilisateurs rapides (30 min chacun)

Le problème principal : les utilisateurs ne voyaient pas le récapitulatif de leur panier pendant la saisie de paiement, et ne faisaient pas confiance au total affiché.

### Solution

Refonte en tunnel multi-étapes avec :
1. **Récapitulatif persistant** visible à chaque étape
2. **Validation inline** des champs (pas d'attente du submit)
3. **Indicateur de progression** clair (3 étapes : Livraison → Paiement → Confirmation)
4. **Intégration Stripe Elements** pour le formulaire CB (confiance + conformité PCI)

```tsx
// Récapitulatif persistant en sidebar sur desktop, sticky en haut sur mobile
function CheckoutLayout({ step, children }) {
  return (
    <div className="checkout-layout">
      <main>{children}</main>
      <aside aria-label="Récapitulatif de commande">
        <CartSummary />
      </aside>
    </div>
  );
}
```

## Résultats

- Taux de drop-off global : **72% → 50%** (−22 points)
- Erreurs de validation signalées en support : −60%
- Temps moyen pour compléter le checkout : 4m12s → 2m48s
