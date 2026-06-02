import { defineCollection, z } from 'astro:content';
import { glob } from 'astro/loaders';

const projets = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/projets' }),
  schema: z.object({
    title: z.string(),
    description: z.string(),
    tags: z.array(z.string()),
    variant: z.enum(['featured', 'sm']),
    mediaClass: z.string(),
    order: z.number(),
    publishDate: z.date(),
    client: z.string().optional(),
    role: z.string().optional(),
    duration: z.string().optional(),
    outcome: z.string().optional(),
  }),
});

const conferences = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/conferences' }),
  schema: z.object({
    title: z.string(),
    occurrences: z.array(z.object({ event: z.string(), date: z.string() })),
    cohost: z.string().optional(),
    cohostUrl: z.string().url().optional(),
    linkLabel: z.string(),
    href: z.string(),
  }),
});

export const collections = { projets, conferences };
