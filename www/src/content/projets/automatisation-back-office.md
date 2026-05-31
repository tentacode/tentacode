---
title: "Back-office assisté par AI"
description: "Workflows internes outillés avec Claude API."
tags: ["claude", "node"]
variant: sm
mediaClass: media-5
order: 5
publishDate: 2024-09-05
client: "Cabinet de conseil, 80 consultants"
role: "Développeur fullstack"
duration: "2 mois"
outcome: "8h/semaine économisées sur les tâches répétitives"
---

## Le contexte

Un cabinet de conseil passait chaque semaine plusieurs heures à des tâches répétitives de traitement de documents : extraction de données depuis des rapports PDF clients, rédaction de synthèses standardisées, mise en forme dans leur CRM interne. Des tâches manuelles, ennuyeuses, et sources d'erreurs.

## Ce que j'ai construit

Un back-office Node.js avec une interface web minimaliste qui automatise trois workflows :

### 1. Extraction de données depuis des PDFs

Les consultants déposent des rapports clients (bilans, cahiers des charges, comptes-rendus). Le système extrait automatiquement les informations structurées (dates, montants, contacts, décisions clés) et les pousse dans le CRM via son API.

```ts
const result = await anthropic.messages.create({
  model: 'claude-opus-4-8',
  max_tokens: 2048,
  system: `Tu es un assistant spécialisé dans l'extraction de données 
           structurées depuis des documents de conseil.`,
  messages: [{
    role: 'user',
    content: [
      { type: 'text', text: 'Extrais les données clés de ce document :' },
      { type: 'document', source: { type: 'base64', media_type: 'application/pdf', data: pdfBase64 } }
    ]
  }]
});
```

### 2. Rédaction de synthèses

À partir des notes brutes d'un consultant post-réunion, le système génère une synthèse structurée (contexte, décisions, prochaines étapes) prête à être envoyée au client. Le consultant relit et valide avant envoi.

### 3. Alertes et suivi

Le système surveille les échéances dans le CRM et génère des résumés hebdomadaires personnalisés par consultant — qui doit relancer qui, quels dossiers sont en retard.

## Résultats

- **8h/semaine** économisées sur les 3 workflows automatisés (mesuré sur 4 semaines)
- Taux d'erreur sur l'extraction de données : < 3% (vs ~12% en saisie manuelle)
- Adoption immédiate par l'équipe — interface volontairement simple, zéro formation requise
