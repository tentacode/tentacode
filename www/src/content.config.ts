import { defineCollection, z } from 'astro:content';
import { glob } from 'astro/loaders';
import { BLOG_CATEGORY_ICON_KEYS } from './types';

const blog = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/blog' }),
  schema: ({ image: img }) => z.object({
    title: z.string(),
    description: z.string(),
    slug: z.string(),
    publishDate: z.date(),
    category: z.string(),
    categoryIcon: z.enum(BLOG_CATEGORY_ICON_KEYS),
    image: img().optional(),
    imageAlt: z.string().optional(),
  }),
});

const projets = defineCollection({
  loader: glob({ pattern: '**/*.md', base: './src/content/projets' }),
  schema: ({ image: img }) => z.object({
    title: z.string(),
    description: z.string(),
    tags: z.array(z.object({ label: z.string(), lang: z.enum(['fr', 'en']) })),
    variant: z.enum(['featured', 'sm']),
    slug: z.string(),
    publishDate: z.date(),
    image: img(),
    imageAlt: z.string(),
    href: z.string().url().optional(),
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

export const collections = { projets, conferences, blog };
