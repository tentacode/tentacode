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
  { name: "PHP / Symfony", category: "Backend", size: "xl" },
  { name: "Laravel", category: "Backend", size: "s" },
  { name: "Node", category: "Backend", size: "m", mono: true },
  { name: "PostgreSQL", category: "Backend", size: "l" },
  { name: "Redis", category: "Backend", size: "s", mono: true },
  { name: "API REST", category: "Backend", size: "m", mono: true },
  // Frontend
  { name: "React", category: "Frontend", size: "l" },
  { name: "TypeScript", category: "Frontend", size: "m", mono: true },
  { name: "Next.js", category: "Frontend", size: "m" },
  { name: "Astro", category: "Frontend", size: "s", mono: true },
  { name: "React Native", category: "Frontend", size: "m", mono: true },
  // Qualité
  { name: "QA & tests E2E", category: "Qualité", size: "l" },
  { name: "Playwright", category: "Qualité", size: "s", mono: true },
  { name: "Cypress", category: "Qualité", size: "s", mono: true },
  // Accessibilité
  { name: "Accessibilité", category: "Accessibilité", size: "xl" },
  { name: "RGAA", category: "Accessibilité", size: "m" },
  { name: "WCAG AA", category: "Accessibilité", size: "s" },
  { name: "Audit", category: "Accessibilité", size: "m" },
  {
    name: "Lecteurs d'écran",
    category: "Accessibilité",
    size: "s",
    mono: true,
  },
  // DevOps
  { name: "Ansible", category: "DevOps", size: "l" },
  { name: "Docker", category: "DevOps", size: "s", mono: true },
  { name: "CI / CD", category: "DevOps", size: "m", mono: true },
  { name: "Github Actions", category: "DevOps", size: "m" },
  // Leadership
  { name: "Lead technique", category: "Leadership", size: "l" },
  { name: "Architecture", category: "Leadership", size: "l", mono: true },
  { name: "Mentorat", category: "Leadership", size: "m" },
  { name: "Pair programming", category: "Leadership", size: "s", mono: true },
  { name: "Pédagogie", category: "Leadership", size: "m" },
  { name: "Recrutement tech", category: "Leadership", size: "s", mono: true },
  { name: "Bienveillance", category: "Leadership", size: "xl" },
  { name: "Pragmatisme", category: "Leadership", size: "m" },
  // IA
  { name: "Claude Code", category: "IA", size: "m" },
  { name: "Dev. Agentique", category: "IA", size: "s" },
];
