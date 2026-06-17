export type SkillSize = "s" | "m" | "l" | "xl";
export type SkillCategory =
  | "Frontend"
  | "Backend"
  | "DevOps"
  | "Qualité"
  | "Accessibilité"
  | "Leadership"
  | "IA";

export interface Skill {
  name: string;
  lang: "fr" | "en";
  category: SkillCategory;
  size: SkillSize;
  mono?: boolean;
}

export const CATEGORY_ORDER: SkillCategory[] = [
  "Backend",
  "Frontend",
  "Accessibilité",
  "Qualité",
  "DevOps",
  "Leadership",
  "IA",
];

export const skills: Skill[] = [
  // Backend
  { name: "PHP / Symfony", lang: "en", category: "Backend", size: "xl" },
  { name: "Laravel", lang: "en", category: "Backend", size: "s" },
  { name: "Node", lang: "en", category: "Backend", size: "m", mono: true },
  { name: "PostgreSQL", lang: "en", category: "Backend", size: "l" },
  { name: "Redis", lang: "en", category: "Backend", size: "s", mono: true },
  { name: "API REST", lang: "fr", category: "Backend", size: "m", mono: true },
  // Frontend
  { name: "React", lang: "en", category: "Frontend", size: "l" },
  { name: "TypeScript", lang: "en", category: "Frontend", size: "m", mono: true },
  { name: "Next.js", lang: "en", category: "Frontend", size: "m" },
  { name: "Astro", lang: "en", category: "Frontend", size: "s", mono: true },
  { name: "React Native", lang: "en", category: "Frontend", size: "m", mono: true },
  // Qualité
  { name: "QA & tests E2E", lang: "fr", category: "Qualité", size: "l" },
  { name: "Playwright", lang: "en", category: "Qualité", size: "s", mono: true },
  { name: "Cypress", lang: "en", category: "Qualité", size: "s", mono: true },
  // Accessibilité
  { name: "Accessibilité", lang: "fr", category: "Accessibilité", size: "xl" },
  { name: "RGAA", lang: "fr", category: "Accessibilité", size: "m" },
  { name: "WCAG AA", lang: "en", category: "Accessibilité", size: "s" },
  { name: "Audits", lang: "fr", category: "Accessibilité", size: "l", mono: true },
  { name: "RAAM", lang: "fr", category: "Accessibilité", size: "m" },
  {
    name: "Lecteurs d'écran",
    lang: "fr",
    category: "Accessibilité",
    size: "s",
    mono: true,
  },
  // DevOps
  { name: "Ansible", lang: "en", category: "DevOps", size: "l" },
  { name: "Docker", lang: "en", category: "DevOps", size: "s", mono: true },
  { name: "CI / CD", lang: "en", category: "DevOps", size: "m", mono: true },
  { name: "Github Actions", lang: "en", category: "DevOps", size: "m" },
  // Leadership
  { name: "Lead technique", lang: "fr", category: "Leadership", size: "l" },
  { name: "Architecture", lang: "fr", category: "Leadership", size: "l", mono: true },
  { name: "Mentorat", lang: "fr", category: "Leadership", size: "m" },
  { name: "Pair programming", lang: "en", category: "Leadership", size: "s", mono: true },
  { name: "Pédagogie", lang: "fr", category: "Leadership", size: "m" },
  { name: "Recrutement tech", lang: "fr", category: "Leadership", size: "s", mono: true },
  { name: "Bienveillance", lang: "fr", category: "Leadership", size: "xl" },
  { name: "Pragmatisme", lang: "fr", category: "Leadership", size: "m" },
  // IA
  { name: "Claude Code", lang: "en", category: "IA", size: "m" },
  { name: "Dev. Agentique", lang: "fr", category: "IA", size: "s" },
];
