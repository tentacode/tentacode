import { getCollection } from 'astro:content';
import type { CollectionEntry } from 'astro:content';
import satori from 'satori';
import { Resvg } from '@resvg/resvg-js';
import * as wawoff2 from 'wawoff2';
import { readFile } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import { BLOG_CATEGORY_ICONS } from '../../components/icons/blogCategoryIcons';
import type { BlogCategoryIcon } from '../../types';

export async function getStaticPaths() {
  const posts = await getCollection('blog');
  return posts.map((post) => ({
    params: { slug: post.data.slug },
    props: { post },
  }));
}

interface Props {
  post: CollectionEntry<'blog'>;
}

const COLORS = {
  bg: '#FFF5F3',
  fg1: '#010101',
  fg3: '#545454',
  accentSoft: '#c3bbf4',
};

function categoryIconSvg(icon: BlogCategoryIcon, color: string) {
  const def = BLOG_CATEGORY_ICONS[icon];
  const fill = def.mode === 'fill' ? color : 'none';
  const stroke = def.mode === 'stroke' ? color : 'none';
  return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="${def.viewBox}" fill="${fill}" stroke="${stroke}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${def.markup}</svg>`;
}

async function loadFont(path: string, weight: number) {
  const url = new URL(path, import.meta.url);
  const woff2Buffer = await readFile(fileURLToPath(url));
  const ttf = await wawoff2.decompress(woff2Buffer);
  return { name: 'card-font', data: Buffer.from(ttf), weight: weight as 400 | 700, style: 'normal' as const };
}

let fontsPromise: ReturnType<typeof loadCardFonts> | undefined;

async function loadCardFonts() {
  return Promise.all([
    loadFont('../../../public/fonts/AtkinsonHyperlegible-Regular.woff2', 400),
    loadFont('../../../public/fonts/AtkinsonHyperlegible-Bold.woff2', 700),
  ]);
}

export async function GET({ props }: { props: Props }) {
  const { title, publishDate, categoryIcon } = props.post.data;
  const fonts = await (fontsPromise ??= loadCardFonts());

  const fmt = new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
  const displayTitle = title.length > 90 ? `${title.slice(0, 87)}…` : title;

  const svg = await satori(
    {
      type: 'div',
      props: {
        style: {
          width: '1200px',
          height: '630px',
          display: 'flex',
          flexDirection: 'column',
          justifyContent: 'space-between',
          background: COLORS.bg,
          padding: '110px',
          fontFamily: 'card-font',
        },
        children: [
          {
            type: 'div',
            props: {
              style: { display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '20px' },
              children: [
                {
                  type: 'div',
                  props: {
                    style: {
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      width: '150px',
                      height: '150px',
                    },
                    children: {
                      type: 'img',
                      props: {
                        width: 150,
                        height: 150,
                        src: `data:image/svg+xml;utf8,${encodeURIComponent(categoryIconSvg(categoryIcon, COLORS.fg1))}`,
                      },
                    },
                  },
                },
                {
                  type: 'span',
                  props: {
                    style: {
                      fontSize: '32px',
                      fontWeight: 700,
                      textTransform: 'uppercase',
                      letterSpacing: '0.06em',
                      color: COLORS.fg3,
                    },
                    children: fmt.format(publishDate),
                  },
                },
              ],
            },
          },
          {
            type: 'div',
            props: {
              style: {
                display: 'flex',
                fontSize: displayTitle.length > 60 ? '52px' : '64px',
                fontWeight: 700,
                lineHeight: 1.15,
                color: COLORS.fg1,
                letterSpacing: '-0.02em',
              },
              children: displayTitle,
            },
          },
        ],
      },
    },
    { width: 1200, height: 630, fonts },
  );

  const png = new Resvg(svg, { fitTo: { mode: 'width', value: 1200 } }).render().asPng();
  return new Response(new Uint8Array(png), {
    headers: { 'Content-Type': 'image/png', 'Cache-Control': 'public, max-age=31536000, immutable' },
  });
}
