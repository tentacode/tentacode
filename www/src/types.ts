export type Lang = "fr" | "en";

export interface Tag {
  label: string;
  lang: Lang;
}

export const BLOG_CATEGORY_ICON_KEYS = ['ai', 'lead', 'a11y'] as const;
export type BlogCategoryIcon = typeof BLOG_CATEGORY_ICON_KEYS[number];
