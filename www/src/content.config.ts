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

export const collections = { projets };
