# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is the tentacode.dev personal website. It consists of:

- `./design/` — Static HTML/CSS design prototype built in Claude Design (temporary, to be implemented in Astro)
- `./www/` — Astro frontend project (to be created)
- `./infrastructure/` — Ansible deployment code (to be created)

## Architecture

The workflow is: implement the design from `./design/` into an Astro project at `./www/`. The design folder contains the finalized HTML/CSS to adapt, not to deploy directly.

### Design Assets

The design lives in `./design/` and includes:
- `index.html` / `tentacode.dev - Landing.html` — main landing page markup
- `assets/css/` — stylesheets (`colors_and_type.css`, `landing.css`, `components.css`)
- `assets/js/` — interactive components (`skills-pile.js`)
- `assets/fonts/` — Lexend Deca variable font
- `assets/img/` — images including portrait and wordmark SVG

When implementing in Astro, preserve the CSS and design fidelity from these files.

## www/ (Astro project — to be created)

Once created, typical commands will be:

```bash
cd www
npm run dev      # start dev server
npm run build    # production build
npm run preview  # preview production build
npm run check    # astro check + ESLint + Playwright smoke test
```

## Coding Rules (www/)

### TypeScript

All code is TypeScript. No plain `.js` files in `src/`.

### Astro Patterns

Follow Astro conventions: pages in `src/pages/`, layouts in `src/layouts/`, components in `src/components/`. Keep component frontmatter minimal — logic that doesn't need to run at build time belongs elsewhere.

### Component Design

Split components into two categories:

- `src/components/ui/` — generic, reusable components with no domain knowledge (e.g. `Button`, `Card`, `Section`)
- `src/components/` — domain components that compose generic ones (e.g. `ContactForm`, `SkillsPile`)

Prefer small, focused components. A component that needs a lot of explanation is probably two components.

Prefer `props` over `slots` for simple content — `<Card title="..." />` is clearer than a named slot when the content is just text. Slots are appropriate when rich markup is needed inside the component.

### Styles

CSS and JS live inside the component they belong to, in `<style>` and `<script>` blocks. Do not extract styles to a separate file unless they are design tokens.

**Style by element tag, not class name**, unless a class is genuinely needed to disambiguate:

```astro
<!-- good -->
<button type="submit">Contactez-moi</button>

<style>
button {
  border: 3px solid #010101;
}
</style>

<!-- bad -->
<button class="cta-button" type="submit">Contactez-moi</button>

<style>
.cta-button {
  border: 3px solid #010101;
}
</style>
```

### Design Tokens

Colors (and other design tokens) are defined as CSS custom properties in `src/styles/tokens.css`, imported once in the root layout. This makes them available in all component `<style>` blocks via `var(--color-name)`. Do not hardcode color values anywhere else.

There is no `global.css`. If a genuine need for global styles arises, ask before creating one.

### Quality (`npm run check`)

`check` runs three things in sequence:

1. `astro check` — TypeScript and Astro diagnostics
2. ESLint — linting
3. Playwright smoke test — visits the homepage, asserts it loads with no console errors

## infrastructure/ (Ansible — to be created)

Ansible playbooks for deploying the Astro build output to the server.
