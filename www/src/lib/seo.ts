import { getImage } from 'astro:assets';
import type { ImageMetadata } from 'astro';
import portraitDefault from '../assets/img/og-default.jpg';

export interface ResolvedOgImage {
  url: string;
  width: number;
  height: number;
}

export async function resolveOgImage(image: ImageMetadata | undefined, site: URL): Promise<ResolvedOgImage> {
  const optimized = await getImage({
    src: image ?? portraitDefault,
    width: 1200,
    height: 630,
    fit: 'cover',
    position: image ? 'center' : 'top',
    format: 'jpg',
  });

  return {
    url: new URL(optimized.src, site).toString(),
    width: 1200,
    height: 630,
  };
}
